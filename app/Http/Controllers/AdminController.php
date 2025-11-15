<?php

namespace App\Http\Controllers;

use App\Models\Akun;
use App\Models\Properti;
use App\Models\Pemesanan;
use App\Models\Review;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function dashboard(): View
    {
        $totalUsers = Akun::count();
        $totalProperti = Properti::count();
        $totalPemesanan = Pemesanan::count();
        $totalReview = Review::count();

        // Statistik untuk grafik
        $pemesananPerBulan = Pemesanan::selectRaw('MONTH(created_at) as bulan, COUNT(*) as total')
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        $revenuePerBulan = Pemesanan::selectRaw('MONTH(created_at) as bulan, COUNT(*) as total')
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalProperti',
            'totalPemesanan',
            'totalReview',
            'pemesananPerBulan',
            'revenuePerBulan'
        ));
    }
}
