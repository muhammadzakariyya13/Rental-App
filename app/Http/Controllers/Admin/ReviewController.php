<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;

class ReviewController extends Controller
{
    public function index()
    {
        $items = Review::with(['akun','properti'])->orderByDesc('id')->paginate(10);
        return view('admin.reviews.index', compact('items'));
    }

    public function destroy($id)
    {
        $item = Review::findOrFail($id);
        $item->delete();
        return back()->with('status','Review dihapus');
    }
}
