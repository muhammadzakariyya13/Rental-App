<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Properti;
use App\Models\Pemesanan;

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

    public function book($id)
    {
        $properti = Properti::findOrFail($id);
        
        // Check if property is available (status should be 'tersedia')
        if ($properti->status !== 'tersedia') {
            return redirect()->back()->with('error', 'Properti tidak tersedia untuk disewa.');
        }
        
        return view('properti.book', compact('properti'));
    }
    
    public function processBooking(Request $request, $id)
    {
        $properti = Properti::findOrFail($id);
        
        // Validate request
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string',
            'tanggal_mulai' => 'required|date|after_or_equal:today',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
            'catatan' => 'nullable|string|max:1000',
            'terms' => 'required|accepted',
        ]);
        
        // Check if property is still available
        if ($properti->status !== 'tersedia') {
            return redirect()->back()->with('error', 'Properti tidak tersedia untuk disewa.');
        }
        
        // Calculate rental duration and total cost
        $startDate = new \DateTime($request->tanggal_mulai);
        $endDate = new \DateTime($request->tanggal_selesai);
        $interval = $startDate->diff($endDate);
        $months = ceil($interval->days / 30);
        $totalCost = $properti->harga * $months;
        
        // Create booking record
        $pemesanan = Pemesanan::create([
            'id_akun' => auth()->id(),
            'id_properti' => $properti->id_properti,
            'tanggal_pemesanan' => now(),
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'lama_sewa' => $months,
            'total_harga' => $totalCost,
            'status' => 'pending',
            'catatan' => $request->catatan,
        ]);
        
        // Update property status to disewa temporarily
        $properti->update(['status' => 'disewa']);
        
        return redirect()->route('payment.create', [
            'order_id' => $pemesanan->id_pemesanan,
            'amount' => $totalCost,
            'customer_name' => auth()->user()->nama,
            'customer_email' => auth()->user()->email,
            'customer_phone' => auth()->user()->phone_number ?? '',
            'description' => 'Pembayaran Sewa Properti: ' . $properti->nama
        ])->with('success', 'Booking berhasil dibuat! Silakan lakukan pembayaran.');
    }
}