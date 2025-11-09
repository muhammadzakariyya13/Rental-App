<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pemesanan;
use App\Models\Properti;
use Illuminate\Support\Facades\Log;

class ManualController extends Controller
{
    /**
     * Menampilkan form untuk update status pemesanan secara manual
     */
    public function showManualUpdateForm()
    {
        $pemesananList = Pemesanan::orderBy('created_at', 'desc')->take(20)->get();
        return view('manual-update', compact('pemesananList'));
    }

    /**
     * Memproses update status pemesanan secara manual
     */
    public function updatePemesananStatus(Request $request)
    {
        $request->validate([
            'id_pemesanan' => 'required|exists:pemesanan,id_pemesanan',
            'status' => 'required|in:pending,confirmed,cancelled,completed',
            'payment_status' => 'required|in:unpaid,pending,paid,failed,cancelled'
        ]);

        try {
            $pemesanan = Pemesanan::find($request->id_pemesanan);
            $oldStatus = $pemesanan->status;
            $pemesanan->status = $request->status;
            $pemesanan->payment_status = $request->payment_status;
            
            // Jika status berubah menjadi confirmed, update properti juga
            if ($request->status === 'confirmed' && $oldStatus !== 'confirmed') {
                $properti = $pemesanan->properti;
                if ($properti) {
                    $properti->status = 'disewa';
                    $properti->save();
                    
                    Log::info('Property status updated to disewa', [
                        'properti_id' => $properti->id_properti,
                        'pemesanan_id' => $pemesanan->id_pemesanan
                    ]);
                }
            }
            
            $pemesanan->save();
            
            Log::info('Manual status update', [
                'pemesanan_id' => $pemesanan->id_pemesanan,
                'old_status' => $oldStatus,
                'new_status' => $request->status,
                'payment_status' => $request->payment_status,
                'user' => auth()->check() ? auth()->user()->email : 'guest'
            ]);
            
            return redirect()->back()->with('success', 'Status pemesanan berhasil diperbarui');
            
        } catch (\Exception $e) {
            Log::error('Manual status update failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()->with('error', 'Gagal memperbarui status: ' . $e->getMessage());
        }
    }
}
