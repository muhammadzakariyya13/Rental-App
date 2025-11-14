<?php

namespace App\Http\Controllers\Pemilik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Sewa;
use App\Models\Properti;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PemesananController extends Controller
{
    public function index()
    {
        // EXISTING CODE - TETAP SAMA
        $pemesanan = Sewa::with(['penyewa', 'properti'])
            ->whereHas('properti', function($query) {
                $query->where('pemilik_id', Auth::id());
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // EXISTING STATS - TETAP SAMA
        $totalPemesanan = Sewa::whereHas('properti', function($q) {
            $q->where('pemilik_id', Auth::id());
        })->count();

        $pemesananPending = Sewa::whereHas('properti', function($q) {
            $q->where('pemilik_id', Auth::id());
        })->where('status', 'pending')->count();

        $pemesananDiterima = Sewa::whereHas('properti', function($q) {
            $q->where('pemilik_id', Auth::id());
        })->where('status', 'diterima')->count();

        $pendapatanBulanIni = Sewa::whereHas('properti', function($q) {
            $q->where('pemilik_id', Auth::id());
        })
        ->where('status', 'diterima')
        ->whereMonth('created_at', now()->month)
        ->whereYear('created_at', now()->year)
        ->sum('total_harga');

        // ========== TAMBAHAN FITUR BARU ==========
        
        // Advanced Analytics
        $pendapatanHariIni = Sewa::whereHas('properti', function($q) {
            $q->where('pemilik_id', Auth::id());
        })
        ->where('status', 'diterima')
        ->whereDate('created_at', today())
        ->sum('total_harga');

        $pendapatanMingguIni = Sewa::whereHas('properti', function($q) {
            $q->where('pemilik_id', Auth::id());
        })
        ->where('status', 'diterima')
        ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
        ->sum('total_harga');

        $pendapatanTahunIni = Sewa::whereHas('properti', function($q) {
            $q->where('pemilik_id', Auth::id());
        })
        ->where('status', 'diterima')
        ->whereYear('created_at', now()->year)
        ->sum('total_harga');

        // Chart data for last 12 months
        $monthlyData = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $revenue = Sewa::whereHas('properti', function($q) {
                $q->where('pemilik_id', Auth::id());
            })
            ->where('status', 'diterima')
            ->whereYear('created_at', $date->year)
            ->whereMonth('created_at', $date->month)
            ->sum('total_harga');
            
            $monthlyData[] = [
                'month' => $date->format('M Y'),
                'revenue' => $revenue
            ];
        }

        // Property performance
        $propertyPerformance = Sewa::whereHas('properti', function($q) {
            $q->where('pemilik_id', Auth::id());
        })
        ->where('status', 'diterima')
        ->with('properti')
        ->groupBy('id_properti')
        ->selectRaw('id_properti, COUNT(*) as booking_count, SUM(total_harga) as total_revenue')
        ->orderByDesc('total_revenue')
        ->get()
        ->map(function($item) {
            return [
                'name' => $item->properti->nama,
                'bookings' => $item->booking_count,
                'revenue' => $item->total_revenue
            ];
        });

        // Growth calculations
        $bulanLalu = Sewa::whereHas('properti', function($q) {
            $q->where('pemilik_id', Auth::id());
        })
        ->where('status', 'diterima')
        ->whereMonth('created_at', now()->subMonth()->month)
        ->whereYear('created_at', now()->subMonth()->year)
        ->sum('total_harga');

        $growthBulanan = $bulanLalu > 0 ? (($pendapatanBulanIni - $bulanLalu) / $bulanLalu) * 100 : 0;
        
        // Calculations
        $rataRataPendapatan = $pemesananDiterima > 0 ? $pendapatanTahunIni / $pemesananDiterima : 0;
        $propertiTerpopuler = $propertyPerformance->first()['name'] ?? 'Belum ada data';

        // Target (bisa diambil dari setting)
        $targetBulanan = 25000000; // 25 juta
        $persentaseTarget = $targetBulanan > 0 ? ($pendapatanBulanIni / $targetBulanan) * 100 : 0;

        // ========== END TAMBAHAN ==========

        return view('pemilik.pemesanan.index', compact(
            // EXISTING VARIABLES
            'pemesanan',
            'totalPemesanan',
            'pemesananPending', 
            'pemesananDiterima',
            'pendapatanBulanIni',
            
            // NEW VARIABLES
            'pendapatanHariIni',
            'pendapatanMingguIni', 
            'pendapatanTahunIni',
            'monthlyData',
            'propertyPerformance',
            'rataRataPendapatan',
            'propertiTerpopuler',
            'targetBulanan',
            'persentaseTarget',
            'growthBulanan'
        ));
    }

    // NEW METHOD untuk AJAX
    public function chartData(Request $request)
    {
        $type = $request->type;
        
        if ($type === 'monthly') {
            $data = [];
            for ($i = 11; $i >= 0; $i--) {
                $date = now()->subMonths($i);
                $revenue = Sewa::whereHas('properti', function($q) {
                    $q->where('pemilik_id', Auth::id());
                })
                ->where('status', 'diterima')
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->sum('total_harga');
                
                $data[] = [
                    'label' => $date->format('M Y'),
                    'value' => $revenue
                ];
            }
            return response()->json($data);
        }
        
        return response()->json([]);
    }
}