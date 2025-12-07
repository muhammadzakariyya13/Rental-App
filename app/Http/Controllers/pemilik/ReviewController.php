<?php

namespace App\Http\Controllers\Pemilik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Review;
use App\Models\Properti;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        // Build query untuk reviews milik pemilik - SESUAI DB STRUCTURE
        $query = Review::with(['penyewa', 'properti'])
            ->whereHas('properti', function($q) {
                $q->where('pemilik_id', Auth::id());
            });

        // Apply filters
        if ($request->filled('rating')) {
            $query->where('rating', $request->rating);
        }

        if ($request->filled('status')) {
            switch ($request->status) {
                case 'replied':
                    $query->whereNotNull('pemilik_reply');
                    break;
                case 'pending':
                    $query->whereNull('pemilik_reply');
                    break;
            }
        }

        if ($request->filled('properti')) {
            $query->where('id_properti', $request->properti);
        }

        if ($request->filled('search')) {
            $query->where('review', 'like', '%' . $request->search . '%');
        }

        $reviews = $query->orderBy('tanggal_review', 'desc')->paginate(10);

        // Calculate stats
        $baseQuery = Review::whereHas('properti', function($q) {
            $q->where('pemilik_id', Auth::id());
        });

        $stats = [
            'total_reviews' => (clone $baseQuery)->count(),
            'average_rating' => round((clone $baseQuery)->avg('rating') ?? 0, 1),
            'pending_replies' => (clone $baseQuery)->whereNull('pemilik_reply')->count(),
            'reviews_bulan_ini' => (clone $baseQuery)->whereMonth('tanggal_review', now()->month)->count(),
            'replied_reviews' => (clone $baseQuery)->whereNotNull('pemilik_reply')->count()
        ];

        // Rating distribution
        $ratingDistribution = [];
        for ($i = 1; $i <= 5; $i++) {
            $count = (clone $baseQuery)->where('rating', $i)->count();
            $ratingDistribution[$i] = [
                'count' => $count,
                'percentage' => $stats['total_reviews'] > 0 ? ($count / $stats['total_reviews']) * 100 : 0
            ];
        }

        // Monthly trend (last 6 months)
        $monthlyTrend = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $count = (clone $baseQuery)
                ->whereYear('tanggal_review', $date->year)
                ->whereMonth('tanggal_review', $date->month)
                ->count();
            
            $monthlyTrend[] = [
                'month' => $date->format('M Y'),
                'count' => $count,
                'short' => $date->format('M')
            ];
        }

        // Get properties list for filter
        $propertiList = Properti::where('pemilik_id', Auth::id())->get(['id_properti', 'nama']);

        return view('pemilik.reviews.index', compact(
            'reviews',
            'stats',
            'ratingDistribution',
            'monthlyTrend',
            'propertiList'
        ));
    }

    public function reply(Request $request, $id)
    {
        $request->validate([
            'pemilik_reply' => 'required|string|max:1000',
        ], [
            'pemilik_reply.required' => 'Balasan tidak boleh kosong',
            'pemilik_reply.max' => 'Balasan maksimal 1000 karakter'
        ]);

        $review = Review::findOrFail($id);
        
        // Check ownership
        if ($review->properti->pemilik_id !== Auth::id()) {
            abort(403);
        }

        $review->update([
            'pemilik_reply' => $request->pemilik_reply,
            'reply_date' => now()
        ]);

        return back()->with('success', '✅ Balasan berhasil dikirim!');
    }

    public function updateApproval(Request $request, $id)
    {
        $request->validate([
            'is_approved' => 'required|boolean'
        ]);

        $review = Review::findOrFail($id);
        
        if ($review->properti->pemilik_id !== Auth::id()) {
            abort(403);
        }

        $review->update(['is_approved' => $request->is_approved]);

        $status = $request->is_approved ? 'disetujui' : 'tidak disetujui';
        return back()->with('success', "✅ Review berhasil {$status}!");
    }

    public function destroy($id)
    {
        $review = Review::findOrFail($id);
        
        if ($review->properti->pemilik_id !== Auth::id()) {
            abort(403);
        }

        $review->delete();
        
        return back()->with('success', '✅ Review berhasil dihapus!');
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'review_ids' => 'required|array|min:1',
            'action' => 'required|in:delete'
        ]);

        $reviewIds = $request->review_ids;
        $action = $request->action;

        // Verify ownership
        $reviews = Review::whereIn('id_review', $reviewIds)
            ->whereHas('properti', function($query) {
                $query->where('pemilik_id', Auth::id());
            })
            ->get();

        if ($reviews->count() !== count($reviewIds)) {
            return response()->json(['success' => false, 'message' => 'Beberapa review tidak ditemukan']);
        }

        $updated = 0;

        if ($action === 'delete') {
            $updated = Review::whereIn('id_review', $reviewIds)
                ->whereHas('properti', function($query) {
                    $query->where('pemilik_id', Auth::id());
                })
                ->delete();
        }

        return response()->json([
            'success' => true,
            'message' => "✅ Berhasil menghapus {$updated} review"
        ]);
    }
}