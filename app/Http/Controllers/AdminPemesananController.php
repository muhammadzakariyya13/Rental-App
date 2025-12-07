<?php

namespace App\Http\Controllers;

use App\Models\Pemesanan;
use App\Models\Properti;
use App\Models\Akun;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class AdminPemesananController extends Controller
{
    /**
     * Get status class berdasarkan status pemesanan
     */
    public function getStatusClass($status)
    {
        return match($status) {
            'diterima' => 'bg-green-100 text-green-800',
            'pending' => 'bg-yellow-100 text-yellow-800',
            'ditolak' => 'bg-red-100 text-red-800',
            default => 'bg-blue-100 text-blue-800',
        };
    }

    /**
     * Get status class untuk pembayaran
     */
    public function getPaymentStatusClass($status)
    {
        return $status == 'sudah_bayar' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';
    }

    public function index(Request $request): View
    {
        $query = Pemesanan::with(['properti', 'penyewa']);

        // Filter by status if provided
        if ($request->has('status') && $request->status !== 'semua') {
            $query->where('status_pembayaran', $request->status);
        }

        $pemesanan = $query->latest('id_pemesanan')->paginate(15);
        
        return view('admin.pemesanan.index', compact('pemesanan'));
    }

    public function show(Pemesanan $pemesanan): View
    {
        $pemesanan->load(['penyewa', 'properti', 'kontrak']);
        return view('admin.pemesanan.show', compact('pemesanan'));
    }

    public function destroy(Pemesanan $pemesanan): RedirectResponse
    {
        $pemesanan->delete();
        return redirect()->route('admin.pemesanan.index')->with('success', 'Pemesanan berhasil dihapus');
    }
}
