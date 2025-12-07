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
        try {
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
                'status' => 'required|in:tersedia,disewa',
                'gambar' => 'required|file|image|max:10240', // 10MB max
            ]);

            // Handle file upload - simpan sebagai file
            if ($request->hasFile('gambar')) {
                $file = $request->file('gambar');
                $filename = 'properti_' . time() . '.' . $file->getClientOriginalExtension();
                
                // Pastikan folder ada
                $destinationPath = public_path('storage/properti');
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }
                
                $file->move($destinationPath, $filename);
                $validated['gambar'] = 'storage/properti/' . $filename;
            }

            Properti::create($validated);

            return redirect()->route('admin.properti.index')->with('success', 'Properti berhasil ditambahkan');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan properti: ' . $e->getMessage());
        }
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
            'status' => 'required|in:tersedia,disewa',
            'gambar' => 'nullable|file|image|max:10240',
        ]);

        // Handle file upload - simpan sebagai file
        if ($request->hasFile('gambar')) {
            // Hapus gambar lama jika ada
            if ($properti->gambar && file_exists(public_path($properti->gambar))) {
                unlink(public_path($properti->gambar));
            }
            
            $file = $request->file('gambar');
            $filename = 'properti_' . $properti->id_properti . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('storage/properti'), $filename);
            $validated['gambar'] = 'storage/properti/' . $filename;
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
