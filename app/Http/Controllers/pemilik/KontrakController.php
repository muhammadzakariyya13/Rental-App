<?php

namespace App\Http\Controllers\Pemilik;

use App\Http\Controllers\Controller;
use App\Models\Pemesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class KontrakController extends Controller
{
    /**
     * Display a listing of contracts (paid bookings).
     */
    public function index(Request $request)
    {
        $pemilik_id = Auth::id();
        
        // Query pemesanan yang sudah dibayar untuk properti milik pemilik yang login
        $query = Pemesanan::with(['akun', 'properti'])
            ->whereHas('properti', function($q) use ($pemilik_id) {
                $q->where('pemilik_id', $pemilik_id);
            })
            ->where('status_pembayaran', 'sudah_bayar')
            ->where('status_pemesanan', 'confirmed');
        
        // Filter by search (username or email)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('akun', function($q) use ($search) {
                $q->where('username', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        // Filter by property
        if ($request->filled('properti')) {
            $query->where('id_properti', $request->properti);
        }
        
        // Filter by download permission
        if ($request->filled('izin')) {
            if ($request->izin === 'diberikan') {
                $query->where('download_izin', true);
            } elseif ($request->izin === 'belum') {
                $query->where('download_izin', false);
            }
        }
        
        $kontrak = $query->latest()->paginate(10);
        
        // Get properti list for filter
        $propertiList = \App\Models\Properti::where('pemilik_id', $pemilik_id)
            ->select('id_properti', 'nama')
            ->get();
        
        // Statistics
        $stats = [
            'total' => Pemesanan::whereHas('properti', function($q) use ($pemilik_id) {
                $q->where('pemilik_id', $pemilik_id);
            })->where('status_pembayaran', 'sudah_bayar')
              ->where('status_pemesanan', 'confirmed')->count(),
            
            'izin_diberikan' => Pemesanan::whereHas('properti', function($q) use ($pemilik_id) {
                $q->where('pemilik_id', $pemilik_id);
            })->where('status_pembayaran', 'sudah_bayar')
              ->where('status_pemesanan', 'confirmed')
              ->where('download_izin', true)->count(),
            
            'belum_izin' => Pemesanan::whereHas('properti', function($q) use ($pemilik_id) {
                $q->where('pemilik_id', $pemilik_id);
            })->where('status_pembayaran', 'sudah_bayar')
              ->where('status_pemesanan', 'confirmed')
              ->where('download_izin', false)->count(),
        ];
        
        return view('pemilik.kontrak.index', compact('kontrak', 'propertiList', 'stats'));
    }
    
    /**
     * Display the specified contract.
     */
    public function show($id)
    {
        $pemesanan = Pemesanan::with(['akun', 'properti.pemilik'])
            ->where('id_pemesanan', $id)
            ->whereHas('properti', function($q) {
                $q->where('pemilik_id', Auth::id());
            })
            ->where('status_pembayaran', 'sudah_bayar')
            ->where('status_pemesanan', 'confirmed')
            ->firstOrFail();
        
        $tanggal_mulai = $pemesanan->tanggal_pemesanan;
        $tanggal_selesai = $pemesanan->tanggal_pemesanan->copy()->addMonths($pemesanan->lama_sewa);
        
        // Get pemilik and penyewa data
        $pemilik_nama = $pemesanan->properti->pemilik->username ?? $pemesanan->properti->pemilik->email ?? 'Pemilik Properti';
        $pemilik_telepon = $pemesanan->properti->pemilik->phone_number ?? '-';
        
        $penyewa_nama = $pemesanan->akun->username ?? $pemesanan->akun->email;
        $penyewa_telepon = $pemesanan->akun->phone_number ?? '-';
        
        return view('pemilik.kontrak.show', compact(
            'pemesanan', 
            'tanggal_mulai', 
            'tanggal_selesai',
            'pemilik_nama',
            'pemilik_telepon',
            'penyewa_nama',
            'penyewa_telepon'
        ));
    }
    
    /**
     * Download contract as PDF.
     */
    public function download($id)
    {
        $pemesanan = Pemesanan::with(['akun', 'properti.pemilik'])
            ->where('id_pemesanan', $id)
            ->whereHas('properti', function($q) {
                $q->where('pemilik_id', Auth::id());
            })
            ->where('status_pembayaran', 'sudah_bayar')
            ->where('status_pemesanan', 'confirmed')
            ->firstOrFail();
        
        $tanggal_mulai = $pemesanan->tanggal_pemesanan;
        $tanggal_selesai = $pemesanan->tanggal_pemesanan->copy()->addMonths($pemesanan->lama_sewa);
        
        // Get pemilik and penyewa data
        $pemilik_nama = $pemesanan->properti->pemilik->username ?? $pemesanan->properti->pemilik->email ?? 'Pemilik Properti';
        $pemilik_telepon = $pemesanan->properti->pemilik->phone_number ?? '-';
        
        $penyewa_nama = $pemesanan->akun->username ?? $pemesanan->akun->email;
        $penyewa_telepon = $pemesanan->akun->phone_number ?? '-';
        
        $pdf = PDF::loadView('penyewa.kontrak.pdf', compact(
            'pemesanan',
            'tanggal_mulai',
            'tanggal_selesai',
            'pemilik_nama',
            'pemilik_telepon',
            'penyewa_nama',
            'penyewa_telepon'
        ));
        
        return $pdf->download('Kontrak_' . $pemesanan->transaction_id . '.pdf');
    }
    
    /**
     * Toggle download permission for tenant.
     */
    public function togglePermission($id)
    {
        $pemesanan = Pemesanan::where('id_pemesanan', $id)
            ->whereHas('properti', function($q) {
                $q->where('pemilik_id', Auth::id());
            })
            ->where('status_pembayaran', 'sudah_bayar')
            ->where('status_pemesanan', 'confirmed')
            ->firstOrFail();
        
        // Toggle permission
        $pemesanan->download_izin = !$pemesanan->download_izin;
        $pemesanan->save();
        
        $status = $pemesanan->download_izin ? 'diberikan' : 'dicabut';
        
        return back()->with('success', "Izin download kontrak berhasil {$status} untuk penyewa {$pemesanan->akun->username}");
    }
}
