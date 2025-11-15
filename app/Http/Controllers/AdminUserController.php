<?php

namespace App\Http\Controllers;

use App\Models\Akun;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class AdminUserController extends Controller
{
    public function index(): View
    {
        $users = Akun::with('roles')->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    public function create(): View
    {
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'username' => 'required|string|max:255|unique:akun,username',
            'email' => 'required|email|unique:akun,email',
            'password' => 'required|string|min:8|confirmed',
            'phone_number' => 'required|string|max:20',
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,id',
        ]);

        $validated['password'] = bcrypt($validated['password']);
        $roles = $validated['roles'];
        unset($validated['roles']);
        
        $user = Akun::create($validated);
        $user->roles()->sync($roles);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil ditambahkan');
    }

    public function show(Akun $user): View
    {
        return view('admin.users.show', compact('user'));
    }

    public function edit(Akun $user): View
    {
        $roles = Role::all();
        $userRoles = $user->roles->pluck('id')->toArray();
        return view('admin.users.edit', compact('user', 'roles', 'userRoles'));
    }

    public function update(Request $request, Akun $user): RedirectResponse
    {
        $validated = $request->validate([
            'username' => 'required|string|max:255|unique:akun,username,' . $user->id,
            'email' => 'required|email|unique:akun,email,' . $user->id,
            'phone_number' => 'required|string|max:20',
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,id',
        ]);

        $roles = $validated['roles'];
        unset($validated['roles']);
        
        $user->update($validated);
        $user->roles()->sync($roles);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil diupdate');
    }

    public function destroy(Akun $user): RedirectResponse
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus');
    }
}
