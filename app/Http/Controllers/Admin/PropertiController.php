<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Properti;

class PropertiController extends Controller
{
    public function index()
    {
        $items = Properti::orderByDesc('id_properti')->paginate(10);
        return view('admin.properti.index', compact('items'));
    }

    public function create()
    {
        return view('admin.properti.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama' => ['required','string','max:255'],
            'harga' => ['required','numeric','min:0'],
            'deskripsi' => ['required','string'],
            'foto' => ['nullable','string','max:255'],
            'status' => ['required','in:tersedia,disewa'],
        ]);
        Properti::create($data);
        return redirect()->route('admin.properti.index')->with('status','Properti ditambahkan');
    }

    public function edit($id)
    {
        $item = Properti::where('id_properti',$id)->firstOrFail();
        return view('admin.properti.edit', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $item = Properti::where('id_properti',$id)->firstOrFail();
        $data = $request->validate([
            'nama' => ['required','string','max:255'],
            'harga' => ['required','numeric','min:0'],
            'deskripsi' => ['required','string'],
            'foto' => ['nullable','string','max:255'],
            'status' => ['required','in:tersedia,disewa'],
        ]);
        $item->update($data);
        return redirect()->route('admin.properti.index')->with('status','Properti diperbarui');
    }

    public function destroy($id)
    {
        $item = Properti::where('id_properti',$id)->firstOrFail();
        try {
            $item->delete();
            return redirect()->route('admin.properti.index')->with('status','Properti dihapus');
        } catch (\Throwable $e) {
            return back()->withErrors('Gagal menghapus: '.$e->getMessage());
        }
    }
}
