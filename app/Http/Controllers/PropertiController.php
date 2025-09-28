<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Properti;

class PropertiController extends Controller
{
    public function index()
    {
        $propertis = Properti::all();
        return view('properti.index', compact('propertis'));
    }

    public function show($id)
    {
        $properti = Properti::findOrFail($id);
        return view('properti.show', compact('properti'));
    }
}