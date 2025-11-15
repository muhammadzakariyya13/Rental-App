<?php

namespace App\Http\Controllers\Penyewa;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Properti;
use App\Models\Pemesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $reviews = Review::where('id_akun', $user->id)
            ->with(['properti', 'akun'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Get properties where user has made a booking
        $bookings = Pemesanan::where('id_akun', $user->id)
            ->where('status', 'diterima')
            ->with('properti')
            ->get();
        
        $properties = $bookings->pluck('properti')->unique();

        return view('penyewa.reviews.index', compact('reviews', 'properties'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'properti_id' => 'required|exists:properti,id_properti',
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'required|string|min:10',
        ]);

        $user = Auth::user();

        Review::create([
            'id_properti' => $request->properti_id,
            'id_akun' => $user->id,
            'rating' => $request->rating,
            'isi_review' => $request->review,
            'is_approved' => false, // Admin harus approve
        ]);

        return redirect()->route('penyewa.reviews')
            ->with('success', 'Review berhasil dikirim dan menunggu persetujuan admin');
    }

    public function edit($id)
    {
        $review = Review::findOrFail($id);
        
        if ($review->id_akun != Auth::user()->id) {
            abort(403, 'Unauthorized');
        }

        $bookings = Pemesanan::where('id_akun', Auth::user()->id)
            ->where('status', 'diterima')
            ->with('properti')
            ->get();
        
        $properties = $bookings->pluck('properti')->unique();

        return view('penyewa.reviews.edit', compact('review', 'properties'));
    }

    public function update(Request $request, $id)
    {
        $review = Review::findOrFail($id);
        
        if ($review->id_akun != Auth::user()->id) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'required|string|min:10',
        ]);

        $review->update([
            'rating' => $request->rating,
            'isi_review' => $request->review,
            'is_approved' => false, // Re-review after edit
        ]);

        return redirect()->route('penyewa.reviews')
            ->with('success', 'Review berhasil diperbarui');
    }

    public function destroy($id)
    {
        $review = Review::findOrFail($id);
        
        if ($review->id_akun != Auth::user()->id) {
            abort(403, 'Unauthorized');
        }

        $review->delete();

        return redirect()->route('penyewa.reviews')
            ->with('success', 'Review berhasil dihapus');
    }
}
