<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Akun;
use App\Models\Role;
use Illuminate\Http\Request;

class AkunController extends Controller
{
    public function index()
    {
        $items = Akun::with('role')->orderByDesc('id')->paginate(10);
        return view('admin.akun.index', compact('items'));
    }

    public function edit($id)
    {
        $item = Akun::with('role')->findOrFail($id);
        // Pastikan role 'admin' dan 'user' ada
        Role::firstOrCreate(
            ['nama' => 'admin'],
            [
                'nama_tampilan' => 'Administrator',
                'deskripsi' => 'Pengelola sistem',
                'aktif' => true,
            ]
        );
        Role::firstOrCreate(
            ['nama' => 'user'],
            [
                'nama_tampilan' => 'Pengguna',
                'deskripsi' => 'Pengguna aplikasi',
                'aktif' => true,
            ]
        );
        // Hanya tampilkan role yang diizinkan (admin & user)
        $roles = Role::whereIn('nama', ['admin','user'])->orderBy('nama')->get();
        return view('admin.akun.edit', compact('item','roles'));
    }

    public function update(Request $request, $id)
    {
        $item = Akun::findOrFail($id);
        $data = $request->validate([
            'nama' => ['required','string','max:255'],
            'phone_number' => ['required','string','max:30'],
            'email' => ['required','email','max:255','unique:akun,email,'.$item->id],
            'username' => ['required','string','max:255','unique:akun,username,'.$item->id],
            'role_id' => ['nullable','exists:role,id'],
        ]);
        $item->update($data);
        // Jika admin mengubah role dirinya sendiri, refresh session agar akses berubah langsung
        if (auth()->id() === $item->id) {
            auth()->setUser($item->fresh('role'));
        }
        return redirect()->route('admin.akun.index')->with('status','Akun diperbarui');
    }

    public function destroy($id)
    {
        $item = Akun::findOrFail($id);
        // Cegah user menghapus dirinya sendiri
        if (auth()->id() === $item->id) {
            return back()->withErrors('Tidak dapat menghapus akun sendiri.');
        }
        try {
            $item->delete();
            return redirect()->route('admin.akun.index')->with('status','Akun dihapus');
        } catch (\Throwable $e) {
            return back()->withErrors('Gagal menghapus akun: '.$e->getMessage());
        }
    }
}
