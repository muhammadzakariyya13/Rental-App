<?php

namespace App\Http\Controllers;

use App\Models\Pemesanan;
use App\Models\Properti;
use App\Models\Akun;
use App\Models\Review;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class AdminLaporanController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();
        
        // Filter data berdasarkan role
        if ($user->hasRole('pemilik')) {
            // Data untuk pemilik - hanya properti milik mereka
            $totalPemesanan = Pemesanan::whereHas('properti', function($q) {
                $q->where('pemilik_id', Auth::id());
            })->count();
            
            $totalRevenue = Pemesanan::whereHas('properti', function($q) {
                $q->where('pemilik_id', Auth::id());
            })->sum('lama_sewa');
            
            $pemesananBulanIni = Pemesanan::whereHas('properti', function($q) {
                $q->where('pemilik_id', Auth::id());
            })->whereMonth('created_at', now()->month)->count();
            
            $propertiTerpopuler = Properti::where('pemilik_id', Auth::id())
                ->withCount('pemesanan')
                ->orderBy('pemesanan_count', 'desc')
                ->take(5)
                ->get();
            
            $pemilikTopProperti = Properti::where('pemilik_id', Auth::id())
                ->withCount('pemesanan')
                ->orderBy('pemesanan_count', 'desc')
                ->take(5)
                ->get();
            
            $ratingRataRata = Review::whereHas('properti', function($q) {
                $q->where('pemilik_id', Auth::id());
            })->avg('rating');
            
            $pemesananPerBulan = Pemesanan::whereHas('properti', function($q) {
                $q->where('pemilik_id', Auth::id());
            })
            ->selectRaw('MONTH(created_at) as bulan, COUNT(*) as total')
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();
            
            $revenuePerBulan = Pemesanan::whereHas('properti', function($q) {
                $q->where('pemilik_id', Auth::id());
            })
            ->join('properti', 'pemesanan.id_properti', '=', 'properti.id_properti')
            ->selectRaw('MONTH(pemesanan.created_at) as bulan, SUM(properti.harga * pemesanan.lama_sewa) as total')
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();
        } else {
            // Data untuk admin - semua data
            $totalPemesanan = Pemesanan::count();
            $totalRevenue = Pemesanan::sum('lama_sewa');
            $pemesananBulanIni = Pemesanan::whereMonth('created_at', now()->month)->count();
            
            $propertiTerpopuler = Properti::withCount('pemesanan')
                ->orderBy('pemesanan_count', 'desc')
                ->take(5)
                ->get();
            
            $pemilikTopProperti = Akun::withCount('properti')
                ->orderBy('properti_count', 'desc')
                ->take(5)
                ->get();
            
            $ratingRataRata = Review::avg('rating');
            
            $pemesananPerBulan = Pemesanan::selectRaw('MONTH(created_at) as bulan, COUNT(*) as total')
                ->groupBy('bulan')
                ->orderBy('bulan')
                ->get();
            
            $revenuePerBulan = Pemesanan::join('properti', 'pemesanan.id_properti', '=', 'properti.id_properti')
                ->selectRaw('MONTH(pemesanan.created_at) as bulan, SUM(properti.harga * pemesanan.lama_sewa) as total')
                ->groupBy('bulan')
                ->orderBy('bulan')
                ->get();
        }

        return view('admin.laporan.index', compact(
            'totalPemesanan',
            'totalRevenue',
            'pemesananBulanIni',
            'propertiTerpopuler',
            'pemilikTopProperti',
            'ratingRataRata',
            'pemesananPerBulan',
            'revenuePerBulan',
            'user'
        ));
    }
} 