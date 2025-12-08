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

        // 1. Status Pemesanan
        $statusPemesanan = [
            'pending' => Pemesanan::where('status_pemesanan', 'pending')->count(),
            'confirmed' => Pemesanan::where('status_pemesanan', 'confirmed')->count(),
            'cancelled' => Pemesanan::where('status_pemesanan', 'cancelled')->count(),
        ];

        // 2. Status Pembayaran
        $statusPembayaran = [
            'belum_bayar' => Pemesanan::where('status_pembayaran', 'belum_bayar')->count(),
            'sudah_bayar' => Pemesanan::where('status_pembayaran', 'sudah_bayar')->count(),
        ];

        // 3. Properti Terpopuler (Top 5)
        $propertiTerpopuler = Pemesanan::select('id_properti')
            ->selectRaw('COUNT(*) as total_pemesanan')
            ->with('properti:id_properti,nama')
            ->groupBy('id_properti')
            ->orderByDesc('total_pemesanan')
            ->limit(5)
            ->get();

        // 4. Review Per Bulan
        $reviewData = Review::selectRaw('MONTH(created_at) as bulan, COUNT(*) as total')
            ->whereYear('created_at', date('Y'))
            ->groupBy('bulan')
            ->pluck('total', 'bulan');

        // Nama bulan dalam bahasa Indonesia
        $namaBulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        // Buat array lengkap untuk 12 bulan (Review)
        $reviewPerBulan = [];
        for ($i = 1; $i <= 12; $i++) {
            $reviewPerBulan[] = [
                'bulan' => $namaBulan[$i],
                'total' => $reviewData[$i] ?? 0
            ];
        }

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalProperti',
            'totalPemesanan',
            'totalReview',
            'statusPemesanan',
            'statusPembayaran',
            'propertiTerpopuler',
            'reviewPerBulan'
        ));
    }

    public function pendapatan(): View
    {
        // Total Pendapatan Admin dari Biaya Admin (seluruh waktu)
        $totalPendapatanAdmin = Pemesanan::where('status_pemesanan', 'confirmed')
            ->where('status_pembayaran', 'sudah_bayar')
            ->sum('biaya_admin');

        // Pendapatan Admin Bulan Ini
        $pendapatanBulanIni = Pemesanan::where('status_pemesanan', 'confirmed')
            ->where('status_pembayaran', 'sudah_bayar')
            ->whereMonth('paid_at', now()->month)
            ->whereYear('paid_at', now()->year)
            ->sum('biaya_admin');

        // Pendapatan Admin Tahun Ini
        $pendapatanTahunIni = Pemesanan::where('status_pemesanan', 'confirmed')
            ->where('status_pembayaran', 'sudah_bayar')
            ->whereYear('paid_at', now()->year)
            ->sum('biaya_admin');

        // Grafik Pendapatan Admin Per Bulan (12 bulan terakhir)
        $chartData = [];
        $namaBulan = [
            1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr',
            5 => 'Mei', 6 => 'Jun', 7 => 'Jul', 8 => 'Agu',
            9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des'
        ];

        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $pendapatan = Pemesanan::where('status_pemesanan', 'confirmed')
                ->where('status_pembayaran', 'sudah_bayar')
                ->whereMonth('paid_at', $date->month)
                ->whereYear('paid_at', $date->year)
                ->sum('biaya_admin');

            $chartData[] = [
                'bulan' => $namaBulan[$date->month],
                'pendapatan' => $pendapatan
            ];
        }

        // Daftar Transaksi dengan Biaya Admin (untuk tabel)
        $transaksi = Pemesanan::with(['properti', 'akun', 'properti.pemilik'])
            ->where('status_pemesanan', 'confirmed')
            ->where('status_pembayaran', 'sudah_bayar')
            ->orderBy('paid_at', 'desc')
            ->paginate(15);

        return view('admin.pendapatan.index', compact(
            'totalPendapatanAdmin',
            'pendapatanBulanIni',
            'pendapatanTahunIni',
            'chartData',
            'transaksi'
        ));
    }
}
