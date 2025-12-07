<?php

namespace App\Http\Controllers\Penyewa;

use App\Http\Controllers\Controller;
use App\Models\Properti;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $query = Properti::query();

        // Filter berdasarkan tipe properti
        if ($request->has('tipe') && $request->tipe != '') {
            $query->where('tipe', $request->tipe);
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

        return view('penyewa.index', compact('properti'));
    }
}
