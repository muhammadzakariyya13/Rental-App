<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class AdminReviewController extends Controller
{
    public function index(): View
    {
        $reviews = Review::with(['properti.images', 'penyewa'])->paginate(15);
        return view('admin.reviews.index', compact('reviews'));
    }

    public function show(Review $review): View
    {
        $review->load(['properti', 'penyewa']);
        return view('admin.reviews.show', compact('review'));
    }

    public function destroy(Review $review): RedirectResponse
    {
        $review->delete();
        return redirect()->route('admin.reviews.index')->with('success', 'Review berhasil dihapus');
    }
}
