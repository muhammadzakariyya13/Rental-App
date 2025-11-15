<?php

namespace App\Http\Controllers\Penyewa;

use App\Http\Controllers\Controller;
use App\Models\Pemesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PemesananController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        $query = Pemesanan::where('id_akun', $user->id)
            ->with(['properti', 'akun'])
            ->orderBy('created_at', 'desc');

        // Filter berdasarkan status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $pemesanan = $query->paginate(10);

        return view('penyewa.pemesanan.index', compact('pemesanan'));
    }

    public function show($id)
    {
        $pemesanan = Pemesanan::findOrFail($id);
        
        // Check authorization
        if ($pemesanan->id_akun != Auth::user()->id) {
            abort(403, 'Unauthorized');
        }

        return view('penyewa.pemesanan.show', compact('pemesanan'));
    }
}
