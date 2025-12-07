<?php

namespace App\Http\Controllers\Penyewa;

use App\Http\Controllers\Controller;
use App\Models\Pemesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class KontrakController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Hanya tampilkan pemesanan yang sudah confirmed, sudah bayar, DAN sudah diberi izin download
        $query = Pemesanan::where('id_akun', $user->id)
            ->where('status_pemesanan', 'confirmed')
            ->where('status_pembayaran', 'sudah_bayar')
            ->where('download_izin', true)
            ->with(['properti', 'akun'])
            ->orderBy('paid_at', 'desc');

        // Filter berdasarkan status aktif/expired
        if ($request->has('filter') && $request->filter != '') {
            $now = now();
            if ($request->filter == 'aktif') {
                $query->whereRaw('DATE_ADD(tanggal_pemesanan, INTERVAL lama_sewa MONTH) >= ?', [$now]);
            } else if ($request->filter == 'expired') {
                $query->whereRaw('DATE_ADD(tanggal_pemesanan, INTERVAL lama_sewa MONTH) < ?', [$now]);
            }
        }

        $kontrak = $query->paginate(10);

        return view('penyewa.kontrak.index', compact('kontrak'));
    }

    public function show($id)
    {
        $pemesanan = Pemesanan::with(['properti', 'akun'])->findOrFail($id);
        
        // Check authorization
        if ($pemesanan->id_akun != Auth::id()) {
            abort(403, 'Unauthorized');
        }

        // Check if contract is available (must be confirmed, paid, and permission granted)
        if ($pemesanan->status_pemesanan != 'confirmed' || $pemesanan->status_pembayaran != 'sudah_bayar') {
            return redirect()->route('penyewa.kontrak.index')->with('error', 'Kontrak tidak tersedia. Pastikan pembayaran sudah lunas.');
        }

        // Check if download permission is granted by owner
        if (!$pemesanan->download_izin) {
            return redirect()->route('penyewa.kontrak.index')->with('error', 'Anda belum mendapat izin dari pemilik untuk melihat kontrak ini. Silakan hubungi pemilik properti.');
        }

        return view('penyewa.kontrak.show', compact('pemesanan'));
    }

    public function download($id)
    {
        $pemesanan = Pemesanan::with(['properti.pemilik', 'akun'])->findOrFail($id);
        
        // Check authorization
        if ($pemesanan->id_akun != Auth::id()) {
            abort(403, 'Unauthorized');
        }

        // Check if contract is available
        if ($pemesanan->status_pemesanan != 'confirmed' || $pemesanan->status_pembayaran != 'sudah_bayar') {
            return redirect()->route('penyewa.kontrak.index')->with('error', 'Kontrak tidak tersedia.');
        }

        // Check if download permission is granted by owner
        if (!$pemesanan->download_izin) {
            return redirect()->route('penyewa.kontrak.index')->with('error', 'Anda belum mendapat izin dari pemilik untuk mendownload kontrak ini. Silakan hubungi pemilik properti.');
        }

        // Calculate contract dates
        $tanggal_mulai = $pemesanan->tanggal_pemesanan;
        $tanggal_selesai = $pemesanan->tanggal_pemesanan->copy()->addMonths($pemesanan->lama_sewa);

        // Prepare data untuk PDF
        $penyewa_nama = $pemesanan->akun->username ?? $pemesanan->akun->email;
        $penyewa_telepon = $pemesanan->akun->phone_number ?? '-';
        $pemilik_nama = 'Pemilik Properti';
        $pemilik_telepon = '-';
        
        if (isset($pemesanan->properti->pemilik)) {
            $pemilik_nama = $pemesanan->properti->pemilik->username ?? $pemesanan->properti->pemilik->email ?? 'Pemilik Properti';
            $pemilik_telepon = $pemesanan->properti->pemilik->phone_number ?? '-';
        }

        // Generate PDF
        $pdf = Pdf::loadView('penyewa.kontrak.pdf', [
            'pemesanan' => $pemesanan,
            'tanggal_mulai' => $tanggal_mulai,
            'tanggal_selesai' => $tanggal_selesai,
            'penyewa_nama' => $penyewa_nama,
            'penyewa_telepon' => $penyewa_telepon,
            'pemilik_nama' => $pemilik_nama,
            'pemilik_telepon' => $pemilik_telepon,
        ]);

        $pdf->setPaper('a4', 'portrait');

        return $pdf->download('Kontrak-' . $pemesanan->id_pemesanan . '-' . $pemesanan->properti->nama . '.pdf');
    }
}
