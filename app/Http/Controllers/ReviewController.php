<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Properti;

class ReviewController extends Controller
{
	// Form membuat review
	public function create($propertiId)
	{
		$properti = Properti::findOrFail($propertiId);
		return view('reviews.create', compact('properti'));
	}

	// Simpan review (User)
	public function store(Request $request, $propertiId)
	{
		$request->validate([
			'komentar' => ['required', 'string', 'max:1000'],
			'rating' => ['required', 'integer', 'min:1', 'max:5'],
		]);

		$properti = Properti::findOrFail($propertiId);

		Review::create([
			'id_akun' => auth()->id(),
			'id_properti' => $properti->id_properti,
			'komentar' => $request->komentar,
			'rating' => (int) $request->rating,
			'tanggal' => now(),
		]);

		return redirect()->route('properti.show', $properti->id_properti)->with('status', 'Review berhasil ditambahkan');
	}

	// Hapus review (Admin dapat hapus semua, user hanya miliknya)
	public function destroy($id)
	{
		$review = Review::findOrFail($id);
		$user = auth()->user();

		if (!($user->isAdmin() || $review->id_akun === $user->id)) {
			abort(403, 'Tidak diizinkan menghapus review ini');
		}
        
		$review->delete();
		return back()->with('status', 'Review dihapus');
	}
}
