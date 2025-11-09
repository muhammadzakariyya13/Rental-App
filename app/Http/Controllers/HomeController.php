<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Handle the home page request
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function index()
    {
        // Jika user sudah login, redirect ke dashboard sesuai role
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        
        // Jika belum login, redirect ke halaman login
        return redirect()->route('login');
    }

    /**
     * Alternative method untuk redirect berdasarkan role
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function redirectToRoleDashboard()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        
        // Load roles relationship jika belum loaded
        if (!$user->relationLoaded('roles')) {
            $user->load('roles');
        }

        // Redirect berdasarkan role
        if ($user->roles->contains('name', 'admin')) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->roles->contains('name', 'pemilik')) {
            return redirect()->route('pemilik.dashboard');
        } elseif ($user->roles->contains('name', 'penyewa')) {
            return redirect()->route('penyewa.dashboard');
        }

        // Default fallback
        return redirect()->route('dashboard');
    }
}