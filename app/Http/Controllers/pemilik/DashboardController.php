<?php

namespace App\Http\Controllers\Pemilik;

use App\Http\Controllers\Controller;
use App\Models\Properti;
use App\Models\Sewa;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // Hitung data untuk dashboard
        $jumlahProperti = Properti::where('pemilik_id', auth()->id())->count();
        $propertiTersedia = Properti::where('pemilik_id', auth()->id())
                                  ->where('status', 'tersedia')->count();
        $propertiDisewa = Properti::where('pemilik_id', auth()->id())
                                ->where('status', 'disewa')->count();
        
        // PENDAPATAN ANALYTICS - DATA REAL
        $pendapatanHariIni = Sewa::whereHas('properti', function($q) {
            $q->where('pemilik_id', Auth::id());
        })
        ->where('status', 'diterima')
        ->whereDate('created_at', today())
        ->sum('total_harga');

        $pendapatanBulanIni = Sewa::whereHas('properti', function($q) {
            $q->where('pemilik_id', Auth::id());
        })
        ->where('status', 'diterima')
        ->whereMonth('created_at', now()->month)
        ->whereYear('created_at', now()->year)
        ->sum('total_harga');

        $pendapatanTahunIni = Sewa::whereHas('properti', function($q) {
            $q->where('pemilik_id', Auth::id());
        })
        ->where('status', 'diterima')
        ->whereYear('created_at', now()->year)
        ->sum('total_harga');

        // REVIEW DATA
        $reviewTerbaru = Review::whereHas('properti', function($q) {
            $q->where('pemilik_id', Auth::id());
        })->whereNull('pemilik_reply')->count();

        // CHART DATA untuk 6 bulan terakhir
        $chartData = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $revenue = Sewa::whereHas('properti', function($q) {
                $q->where('pemilik_id', Auth::id());
            })
            ->where('status', 'diterima')
            ->whereYear('created_at', $date->year)
            ->whereMonth('created_at', $date->month)
            ->sum('total_harga');
            
            $chartData[] = [
                'month' => $date->format('M Y'),
                'revenue' => $revenue
            ];
        }

        // PROPERTI TERPOPULER berdasarkan booking
        $propertiTerpopuler = Properti::withCount(['sewas as total_bookings' => function($q) {
                $q->where('status', 'diterima');
            }])
            ->where('pemilik_id', Auth::id())
            ->orderBy('total_bookings', 'desc')
            ->take(5)
            ->get();
        
        return view('pemilik.dashboard', compact(
            'jumlahProperti', 
            'propertiTersedia', 
            'propertiDisewa',
            'pendapatanHariIni',
            'pendapatanBulanIni',
            'pendapatanTahunIni',
            'reviewTerbaru',
            'chartData',
            'propertiTerpopuler'
        ));
    }
}