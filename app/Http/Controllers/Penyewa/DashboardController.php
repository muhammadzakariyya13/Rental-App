<?php

namespace App\Http\Controllers\Penyewa;

use App\Http\Controllers\Controller;
use App\Models\Properti;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Hanya ambil 3 properti untuk dashboard
        $properti = Properti::latest()->take(3)->get();

        return view('penyewa.index', compact('properti'));
    }
}
