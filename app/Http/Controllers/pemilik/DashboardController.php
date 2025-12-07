<?php

namespace App\Http\Controllers\Pemilik;

use App\Http\Controllers\Controller;
use App\Models\Properti;
use App\Models\Pemesanan;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // PROPERTI STATS
        $totalProperti = Properti::where('pemilik_id', auth()->id())->count();
        
        // BOOKING STATS
        $activeBookings = Pemesanan::whereHas('properti', function($q) {
            $q->where('pemilik_id', Auth::id());
        })
        ->where('status_pemesanan', 'confirmed')
        ->where('status_pembayaran', 'sudah_bayar')
        ->whereRaw('DATE_ADD(tanggal_pemesanan, INTERVAL lama_sewa MONTH) >= NOW()')
        ->count();

        // PENDAPATAN ANALYTICS - DATA REAL dari Pemesanan
        $monthlyIncome = Pemesanan::whereHas('properti', function($q) {
            $q->where('pemilik_id', Auth::id());
        })
        ->where('status_pemesanan', 'confirmed')
        ->where('status_pembayaran', 'sudah_bayar')
        ->whereMonth('paid_at', now()->month)
        ->whereYear('paid_at', now()->year)
        ->sum('total_harga');

        $pendapatanTahunIni = Pemesanan::whereHas('properti', function($q) {
            $q->where('pemilik_id', Auth::id());
        })
        ->where('status_pemesanan', 'confirmed')
        ->where('status_pembayaran', 'sudah_bayar')
        ->whereYear('paid_at', now()->year)
        ->sum('total_harga');

        // REVIEW DATA - Reviews yang belum dibalas
        $pendingReviews = Review::whereHas('properti', function($q) {
            $q->where('pemilik_id', Auth::id());
        })->whereNull('pemilik_reply')->count();

        // PENDING ACTIONS
        $pendingActions = [
            'new_bookings' => Pemesanan::whereHas('properti', function($q) {
                $q->where('pemilik_id', Auth::id());
            })->where('status_pemesanan', 'pending')->count(),
            'checkout_today' => Pemesanan::whereHas('properti', function($q) {
                $q->where('pemilik_id', Auth::id());
            })
            ->where('status_pemesanan', 'confirmed')
            ->whereRaw('DATE_ADD(tanggal_pemesanan, INTERVAL lama_sewa MONTH) = CURDATE()')
            ->count()
        ];

        // CHART DATA untuk 12 bulan terakhir
        $chartData = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $revenue = Pemesanan::whereHas('properti', function($q) {
                $q->where('pemilik_id', Auth::id());
            })
            ->where('status_pemesanan', 'confirmed')
            ->where('status_pembayaran', 'sudah_bayar')
            ->whereYear('paid_at', $date->year)
            ->whereMonth('paid_at', $date->month)
            ->sum('total_harga');
            
            $chartData[] = [
                'month' => $date->format('M Y'),
                'revenue' => $revenue
            ];
        }

        // TOP PROPERTIES dengan revenue dan booking count
        $propertyStats = Properti::where('pemilik_id', Auth::id())
            ->withCount(['pemesanan as total_bookings' => function($q) {
                $q->where('status_pemesanan', 'confirmed');
            }])
            ->with(['pemesanan' => function($q) {
                $q->where('status_pemesanan', 'confirmed')
                  ->where('status_pembayaran', 'sudah_bayar');
            }])
            ->get()
            ->map(function($properti) {
                $properti->total_revenue = $properti->pemesanan->sum('total_harga');
                $properti->avg_rating = $properti->reviews()->avg('rating') ?? 0;
                return $properti;
            })
            ->sortByDesc('total_revenue')
            ->take(5);

        // RECENT BOOKINGS - 5 pemesanan terbaru
        $recentBookings = Pemesanan::with(['penyewa', 'properti'])
            ->whereHas('properti', function($q) {
                $q->where('pemilik_id', Auth::id());
            })
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // RECENT REVIEWS - 5 review terbaru yang perlu dibalas
        $recentReviews = Review::with(['penyewa', 'properti'])
            ->whereHas('properti', function($q) {
                $q->where('pemilik_id', Auth::id());
            })
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // OCCUPANCY RATE - persentase properti yang sedang aktif disewa
        $occupancyRate = $totalProperti > 0 
            ? ($activeBookings / $totalProperti) * 100 
            : 0;

        // TOTAL REVIEWS COUNT
        $totalReviews = Review::whereHas('properti', function($q) {
            $q->where('pemilik_id', Auth::id());
        })->count();

        return view('pemilik.dashboard', compact(
            'totalProperti',
            'activeBookings',
            'monthlyIncome',
            'pendapatanTahunIni',
            'pendingReviews',
            'pendingActions',
            'chartData',
            'propertyStats',
            'recentBookings',
            'recentReviews',
            'occupancyRate',
            'totalReviews'
        ));
    }
}