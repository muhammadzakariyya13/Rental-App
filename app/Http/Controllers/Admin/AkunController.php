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
        $roles = Role::orderBy('nama')->get();
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
        return redirect()->route('admin.akun.index')->with('status','Akun diperbarui');
    }
}
