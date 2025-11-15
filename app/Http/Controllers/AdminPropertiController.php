<?php

namespace App\Http\Controllers;

use App\Models\Properti;
use App\Models\Akun;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class AdminPropertiController extends Controller
{
    public function index(): View
    {
        $properti = Properti::with('pemilik')->paginate(15);
        return view('admin.properti.index', compact('properti'));
    }

    public function create(): View
    {
        $pemilik = Akun::whereHas('roles', function ($query) {
            $query->where('name', 'pemilik');
        })->get();

        return view('admin.properti.create', compact('pemilik'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'alamat' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'kamar_tidur' => 'nullable|numeric|min:0',
            'kamar_mandi' => 'nullable|numeric|min:0',
            'luas_tanah' => 'nullable|numeric|min:0',
            'tipe' => 'required|in:rumah,apartemen,kontrakan,vila',
            'pemilik_id' => 'required|exists:akun,id',
            'status' => 'required|in:tersedia,disewa,maintenance',
            'gambar' => 'required|file|image|max:5120', // 5MB max
        ]);

        // Handle file upload - konversi ke base64 untuk disimpan di longText column
        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $gambarContent = file_get_contents($file->getRealPath());
            $validated['gambar'] = base64_encode($gambarContent);
        }

        Properti::create($validated);

        return redirect()->route('admin.properti.index')->with('success', 'Properti berhasil ditambahkan');
    }

    public function show(Properti $properti): View
    {
        return view('admin.properti.show', compact('properti'));
    }

    public function edit(Properti $properti): View
    {
        $pemilik = Akun::whereHas('roles', function ($query) {
            $query->where('name', 'pemilik');
        })->get();

        return view('admin.properti.edit', compact('properti', 'pemilik'));
    }

    public function update(Request $request, Properti $properti): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'alamat' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'kamar_tidur' => 'nullable|numeric|min:0',
            'kamar_mandi' => 'nullable|numeric|min:0',
            'luas_tanah' => 'nullable|numeric|min:0',
            'tipe' => 'required|in:rumah,apartemen,kontrakan,vila',
            'pemilik_id' => 'required|exists:akun,id',
            'status' => 'required|in:tersedia,disewa,maintenance',
            'gambar' => 'nullable|file|image|max:5120',
        ]);

        // Handle file upload - konversi ke base64
        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $gambarContent = file_get_contents($file->getRealPath());
            $validated['gambar'] = base64_encode($gambarContent);
        }

        $properti->update($validated);

        return redirect()->route('admin.properti.index')->with('success', 'Properti berhasil diupdate');
    }

    public function destroy(Properti $properti): RedirectResponse
    {
        $properti->delete();
        return redirect()->route('admin.properti.index')->with('success', 'Properti berhasil dihapus');
    }
}
