<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Akun;

class UserProfileController extends Controller
{
    /**
     * Display profile edit form for authenticated user.
     */
    public function edit(Request $request)
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    /**
     * Update authenticated user's profile.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'username' => ['nullable', 'string', 'max:50', 'unique:akun,username,'.$user->id],
            'email' => ['required', 'email', 'max:255', 'unique:akun,email,'.$user->id],
            'phone_number' => ['nullable', 'string', 'max:30'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $user->nama = $data['nama'];
        $user->username = $data['username'] ?? $user->username;
        $user->email = $data['email'];
        $user->phone_number = $data['phone_number'] ?? $user->phone_number;

        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        $user->save();

        if (!empty($data['password'])) {
            Auth::login($user);
        }

        return redirect()->route('profile.edit')->with('success', 'Profil berhasil diperbarui.');
    }
}
