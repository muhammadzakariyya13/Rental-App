<?php

namespace App\Http\Controllers\Pemilik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Properti;
use Illuminate\Support\Facades\Auth;

class PropertiController extends Controller
{
    /**
     * Display a listing of properties.
     */
    public function index()
    {
        $user = Auth::user();
        $properties = Properti::where('id_akun', $user->id)->get();
        return view('pemilik.properti.index', compact('properties'));
    }

    /**
     * Show the form for creating a new property.
     */
    public function create()
    {
        return view('pemilik.properti.create');
    }

    /**
     * Store a newly created property in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string',
            'harga' => 'required|numeric',
            'deskripsi' => 'required|string',
            'status' => 'required|in:tersedia,disewa,tidak tersedia',
            'tipe' => 'required|string|max:255',
            // Add other validation rules as needed
        ]);

        $property = new Properti();
        $property->nama = $validated['nama'];
        $property->alamat = $validated['alamat'];
        $property->harga = $validated['harga'];
        $property->deskripsi = $validated['deskripsi'];
        $property->status = $validated['status'];
        $property->tipe = $validated['tipe'];
        $property->id_akun = Auth::id(); // Set the owner ID
        $property->save();

        return redirect()->route('pemilik.properti.index')
            ->with('success', 'Properti berhasil ditambahkan.');
    }

    /**
     * Display the specified property.
     */
    public function show(string $id)
    {
        $property = Properti::findOrFail($id);
        
        // Check if the property belongs to the authenticated user
        if ($property->id_akun != Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        return view('pemilik.properti.show', compact('property'));
    }

    /**
     * Show the form for editing the specified property.
     */
    public function edit(string $id)
    {
        $property = Properti::findOrFail($id);
        
        // Check if the property belongs to the authenticated user
        if ($property->id_akun != Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        return view('pemilik.properti.edit', compact('property'));
    }

    /**
     * Update the specified property in storage.
     */
    public function update(Request $request, string $id)
    {
        $property = Properti::findOrFail($id);
        
        // Check if the property belongs to the authenticated user
        if ($property->id_akun != Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string',
            'harga' => 'required|numeric',
            'deskripsi' => 'required|string',
            'status' => 'required|in:tersedia,disewa,tidak tersedia',
            'tipe' => 'required|string|max:255',
            // Add other validation rules as needed
        ]);

        $property->nama = $validated['nama'];
        $property->alamat = $validated['alamat'];
        $property->harga = $validated['harga'];
        $property->deskripsi = $validated['deskripsi'];
        $property->status = $validated['status'];
        $property->tipe = $validated['tipe'];
        $property->save();

        return redirect()->route('pemilik.properti.index')
            ->with('success', 'Properti berhasil diperbarui.');
    }

    /**
     * Remove the specified property from storage.
     */
    public function destroy(string $id)
    {
        $property = Properti::findOrFail($id);
        
        // Check if the property belongs to the authenticated user
        if ($property->id_akun != Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        $property->delete();

        return redirect()->route('pemilik.properti.index')
            ->with('success', 'Properti berhasil dihapus.');
    }
}