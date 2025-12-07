<?php

namespace App\Http\Controllers\Penyewa;

use App\Http\Controllers\Controller;
use App\Models\Properti;
use Illuminate\Http\Request;

class BrowseController extends Controller
{
    public function index(Request $request)
    {
        // Show all properties (both tersedia and disewa)
        $query = Properti::query();

        // Filter berdasarkan tipe properti
        if ($request->has('tipe') && $request->tipe != '') {
            $query->where('tipe', $request->tipe);
        }

        // Filter berdasarkan status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Search berdasarkan nama atau lokasi
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('alamat', 'like', "%{$search}%");
            });
        }

        $properti = $query->latest()->paginate(12);

        return view('penyewa.browse.index', compact('properti'));
    }

    public function show($id)
    {
        $properti = Properti::with('pemilik')->findOrFail($id);
        $reviews = $properti->reviews()->with('penyewa')->latest()->get();
        
        return view('penyewa.browse.show', compact('properti', 'reviews'));
    }
}
