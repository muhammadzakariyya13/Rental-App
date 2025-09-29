<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pemesanan;

class PemesananController extends Controller
{
    public function index()
    {
        $items = Pemesanan::with(['akun','properti'])->orderByDesc('id_pemesanan')->paginate(10);
        return view('admin.pemesanan.index', compact('items'));
    }

    public function edit($id)
    {
        $item = Pemesanan::with(['akun','properti'])->where('id_pemesanan',$id)->firstOrFail();
        return view('admin.pemesanan.edit', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $item = Pemesanan::where('id_pemesanan',$id)->firstOrFail();
        $data = $request->validate([
            'status_pemesanan' => ['required','in:pending,confirmed,cancelled'],
            'status_pembayaran' => ['required','in:belum_bayar,sudah_bayar'],
        ]);
        $item->update($data);
        return redirect()->route('admin.pemesanan.index')->with('status','Pemesanan diperbarui');
    }
}
