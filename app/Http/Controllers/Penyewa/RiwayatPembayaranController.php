<?php

namespace App\Http\Controllers\Penyewa;

use App\Http\Controllers\Controller;
use App\Models\Pemesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RiwayatPembayaranController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Query base
        $query = Pemesanan::where('id_akun', $user->id)
            ->with(['properti'])
            ->orderBy('created_at', 'desc');

        // Support both new and legacy param names from the UI
        $statusPemesanan = $request->input('status_pemesanan', $request->input('status'));
        $statusPembayaran = $request->input('status_pembayaran');
        $paymentType = $request->input('payment_type');

        // Filter by status pemesanan (confirmed, pending, cancelled)
        if (!empty($statusPemesanan)) {
            $query->where('status_pemesanan', $statusPemesanan);
        }

        // Filter by status pembayaran (sudah_bayar / belum_bayar)
        if (!empty($statusPembayaran)) {
            $query->where('status_pembayaran', $statusPembayaran);
        }

        // Filter by payment type (credit_card, bank_transfer, gopay, etc.)
        if (!empty($paymentType)) {
            $query->where('payment_type', $paymentType);
        }

        // Clone query BEFORE paginate for summary calculations
        $baseQuery = clone $query;

        // Get paginated results (with current query string)
        $riwayat = $query->paginate(10)->withQueryString();

        // Calculate totals based on the base filtered query
        // Paid total: sudah_bayar and not cancelled
        $totalPaid = (clone $baseQuery)
            ->where('status_pembayaran', 'sudah_bayar')
            ->where('status_pemesanan', '!=', 'cancelled')
            ->sum('total_harga');

        // Pending total: belum_bayar and not cancelled
        $totalPending = (clone $baseQuery)
            ->where('status_pembayaran', 'belum_bayar')
            ->where('status_pemesanan', '!=', 'cancelled')
            ->sum('total_harga');

        return view('penyewa.riwayat-pembayaran.index', compact('riwayat', 'totalPaid', 'totalPending'));
    }
}
