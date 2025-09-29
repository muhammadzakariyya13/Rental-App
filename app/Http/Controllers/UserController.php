<?php
namespace App\Http\Controllers;

use App\Models\Pemesanan;
use App\Models\Kontrak;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // Melihat Pemesanan Sendiri
    public function pemesananSaya()
    {
        $userId = auth()->id();
        $list = Pemesanan::where('id_akun', $userId)->latest()->get();
        return view('user.pemesanan', compact('list'));
    }

    // Melihat Kontrak Sendiri
    public function kontrakSaya()
    {
        $userId = auth()->id();
        $list = Kontrak::whereHas('pemesanan', function ($q) use ($userId) {
            $q->where('id_akun', $userId);
        })->latest()->get();
        return view('user.kontrak', compact('list'));
    }
}
