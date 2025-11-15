<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Akun;
use App\Models\Role;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        $roles = Role::whereNotIn('name', ['admin'])->get();
        return view('auth.register', compact('roles'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'username' => ['required', 'string', 'max:255', 'unique:akun'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:akun'],
            'phone_number' => ['required', 'string', 'max:15'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'exists:roles,id'],
        ]);

        $user = Akun::create([
            'username' => $request->username,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'password' => Hash::make($request->password),
        ]);

        // Assign the selected role
        $role = Role::find($request->role);
        $user->roles()->attach($role);

        event(new Registered($user));

        Auth::login($user);

        // Redirect based on role
        if ($user->hasRole('pemilik')) {
            return redirect(route('pemilik.dashboard', absolute: false));
        } elseif ($user->hasRole('penyewa')) {
            return redirect(route('penyewa.browse', absolute: false));
        }

        return redirect(route('dashboard', absolute: false));
    }
}
