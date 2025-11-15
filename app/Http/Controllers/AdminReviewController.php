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
        $reviews = Review::with(['properti', 'penyewa'])->paginate(15);
        return view('admin.reviews.index', compact('reviews'));
    }

    public function destroy(Review $review): RedirectResponse
    {
        $review->delete();
        return redirect()->route('admin.reviews.index')->with('success', 'Review berhasil dihapus');
    }
}
