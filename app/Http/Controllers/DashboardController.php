<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{    /**
     * Show the appropriate dashboard based on user role.
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function index()
    {
        $user = Auth::user();
        
        if ($user->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->hasRole('pemilik')) {
            return redirect()->route('pemilik.dashboard');
        } elseif ($user->hasRole('penyewa')) {
            return redirect()->route('penyewa.dashboard');
        }
        
        return redirect('/');
    }    /**
     * Show the admin dashboard.
     * 
     * @return \Illuminate\View\View
     */
    public function adminDashboard()
    {
        return view('admin.dashboard');
    }

    /**
     * Show the owner (pemilik) dashboard.
     * 
     * @return \Illuminate\View\View
     */
    public function pemilikDashboard()
    {
        // Get user's properties if needed for dashboard
        // $properties = Auth::user()->properties;
        return view('pemilik.dashboard');
    }

    /**
     * Show the tenant (penyewa) dashboard.
     * 
     * @return \Illuminate\View\View
     */
    public function penyewaDashboard()
    {
        // Get user's bookings if needed for dashboard
        // $bookings = Auth::user()->bookings;
        return view('penyewa.dashboard');
    }
}
