<?php

namespace App\Http\Controllers\Penyewa;

use App\Http\Controllers\Controller;
use App\Models\Properti;
use Illuminate\Http\Request;

class BrowseController extends Controller
{
    /**
     * Display a listing of available properties.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = Properti::where('status', 'tersedia');

        // Handle search
        if ($request->has('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('nama', 'like', "%{$searchTerm}%")
                  ->orWhere('alamat', 'like', "%{$searchTerm}%")
                  ->orWhere('deskripsi', 'like', "%{$searchTerm}%");
            });
        }

        // Handle filtering by property type
        if ($request->has('tipe') && $request->tipe != 'all') {
            $query->where('tipe', $request->tipe);
        }

        // Handle sorting
        $sortField = $request->sort ?? 'harga';
        $sortDirection = $request->direction ?? 'asc';
        $query->orderBy($sortField, $sortDirection);

        // Get all property types for the filter
        $propertyTypes = Properti::select('tipe')->distinct()->pluck('tipe');
        
        // Paginate the results
        $properties = $query->paginate(9);

        return view('penyewa.browse', compact('properties', 'propertyTypes'));
    }

    /**
     * Display the specified property details.
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $property = Properti::findOrFail($id);
        return view('penyewa.property-details', compact('property'));
    }
}
