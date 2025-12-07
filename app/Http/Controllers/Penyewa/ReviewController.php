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

    public function create($id)
    {
        $pemesanan = Pemesanan::with(['properti', 'akun'])
            ->findOrFail($id);
        
        // Verify ownership
        if ($pemesanan->id_akun != Auth::id()) {
            abort(403, 'Unauthorized');
        }

        // Verify booking is paid
        if ($pemesanan->status_pemesanan != 'confirmed' || $pemesanan->status_pembayaran != 'sudah_bayar') {
            return redirect()->route('penyewa.pemesanan.index')
                ->with('error', 'Anda hanya bisa memberikan review setelah pembayaran selesai');
        }

        return view('penyewa.reviews.create', compact('pemesanan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pemesanan_id' => 'nullable|exists:pemesanan,id_pemesanan',
            'properti_id' => 'required|exists:properti,id_properti',
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'required|string|min:10',
        ]);

        $user = Auth::user();

        // If pemesanan_id is provided, verify it belongs to the user
        if ($request->pemesanan_id) {
            $pemesanan = Pemesanan::findOrFail($request->pemesanan_id);
            if ($pemesanan->id_akun != $user->id) {
                abort(403, 'Unauthorized');
            }
        }

        Review::create([
            'id_properti' => $request->properti_id,
            'id_penyewa' => $user->id,
            'id_pemesanan' => $request->pemesanan_id,
            'rating' => $request->rating,
            'review' => $request->review,
            'is_approved' => 1, // Auto-approve (langsung tampil)
        ]);

        return redirect()->route('penyewa.pemesanan.index')
            ->with('success', 'Review berhasil dikirim dan langsung dipublikasikan!');
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
