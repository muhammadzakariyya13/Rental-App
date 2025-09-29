<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kontrak;

class KontrakController extends Controller
{
    public function index()
    {
        $items = Kontrak::with(['properti','pemesanan'])->orderByDesc('id_kontrak')->paginate(10);
        return view('admin.kontrak.index', compact('items'));
    }

    public function show($id)
    {
        $item = Kontrak::with(['properti','pemesanan'])->where('id_kontrak',$id)->firstOrFail();
        return view('admin.kontrak.show', compact('item'));
    }
}
