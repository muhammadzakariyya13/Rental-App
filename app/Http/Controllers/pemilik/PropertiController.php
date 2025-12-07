<?php

namespace App\Http\Controllers\Pemilik;

use App\Http\Controllers\Controller;
use App\Models\Properti;
use Illuminate\Http\Request;
use App\Models\PropertiImage;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PropertiController extends Controller
{
    public function index(Request $request)
    {
        // Query dasar
        $query = Properti::where('pemilik_id', auth()->id());
        
        // Filter search (nama atau alamat)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', '%' . $search . '%')
                  ->orWhere('alamat', 'like', '%' . $search . '%');
            });
        }
        
        // Filter tipe
        if ($request->filled('tipe')) {
            $query->where('tipe', $request->tipe);
        }
        
        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Filter harga
        if ($request->filled('harga_min')) {
            $query->where('harga', '>=', $request->harga_min);
        }
        
        if ($request->filled('harga_max')) {
            $query->where('harga', '<=', $request->harga_max);
        }
        
        // Get paginated data
        $properti = $query->orderBy('created_at', 'desc')->paginate(9)->withQueryString();
        
        // Statistik untuk cards
        $totalProperti = Properti::where('pemilik_id', auth()->id())->count();
        $propertiTersedia = Properti::where('pemilik_id', auth()->id())->where('status', 'tersedia')->count();
        $propertiDisewa = Properti::where('pemilik_id', auth()->id())->where('status', 'disewa')->count();
        $propertiMaintenance = Properti::where('pemilik_id', auth()->id())->where('status', 'maintenance')->count();
        
        // Rata-rata harga
        $rataRataHarga = Properti::where('pemilik_id', auth()->id())->avg('harga') ?? 0;
        
        return view('pemilik.properti.index', compact(
            'properti', 
            'totalProperti', 
            'propertiTersedia', 
            'propertiDisewa', 
            'propertiMaintenance',
            'rataRataHarga'
        ));
    }

    public function create()
    {
        return view('pemilik.properti.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string',
            'harga' => 'required|numeric|min:0',
            'tipe' => 'required|in:rumah,apartemen,kontrakan,vila',
            'kamar_tidur' => 'required|integer|min:0',
            'kamar_mandi' => 'required|integer|min:0',
            'luas_tanah' => 'nullable|integer|min:0',
            'luas_bangunan' => 'nullable|integer|min:0',
            'deskripsi' => 'nullable|string',
            'gambar' => 'nullable|image|mimes:jpeg,jpg,png|max:10240',
        ]);

        $validated['pemilik_id'] = auth()->id();
        $validated['status'] = 'tersedia';
        
        // Handle gambar upload - simpan sebagai file
        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $filename = 'properti_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('storage/properti'), $filename);
            $validated['gambar'] = 'storage/properti/' . $filename;
        } else {
            $validated['gambar'] = null;
        }

        $properti = Properti::create($validated);

        return redirect()->route('pemilik.properti')->with('success', 'Properti berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $properti = Properti::where('id_properti', $id)->where('pemilik_id', auth()->id())->firstOrFail();
        return view('pemilik.properti.edit', compact('properti'));
    }

    public function update(Request $request, $id)
    {
        $properti = Properti::where('id_properti', $id)->where('pemilik_id', auth()->id())->firstOrFail();
        
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string',
            'harga' => 'required|numeric|min:0',
            'tipe' => 'required|in:rumah,apartemen,kontrakan,vila',
            'kamar_tidur' => 'required|integer|min:0',
            'kamar_mandi' => 'required|integer|min:0',
            'luas_tanah' => 'nullable|integer|min:0',
            'luas_bangunan' => 'nullable|integer|min:0',
            'deskripsi' => 'nullable|string',
            'status' => 'required|in:tersedia,disewa',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:10240',
        ]);

        // Handle image upload - simpan sebagai file
        if ($request->hasFile('gambar')) {
            // Hapus gambar lama jika ada
            if ($properti->gambar && file_exists(public_path($properti->gambar))) {
                unlink(public_path($properti->gambar));
            }
            
            $file = $request->file('gambar');
            $filename = 'properti_' . $properti->id_properti . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('storage/properti'), $filename);
            $validated['gambar'] = 'storage/properti/' . $filename;
        }

        $properti->update($validated);

        return redirect()->route('pemilik.properti')->with('success', 'Properti berhasil diupdate!');
    }

    public function destroy($id)
    {
        $properti = Properti::where('id_properti', $id)->where('pemilik_id', auth()->id())->firstOrFail();
        $properti->delete();

        return redirect()->route('pemilik.properti')->with('success', 'Properti berhasil dihapus!');
    }

    // Bulk delete
    public function bulkDelete(Request $request)
    {
        $ids = $request->ids;
        
        if (!$ids) {
            return redirect()->back()->with('error', 'Tidak ada properti yang dipilih!');
        }
        
        $deleted = Properti::whereIn('id_properti', $ids)
                          ->where('pemilik_id', auth()->id())
                          ->delete();
        
        return redirect()->back()->with('success', "$deleted properti berhasil dihapus!");
    }

    public function show($id)
    {
        $properti = Properti::where('id_properti', $id)
            ->where('pemilik_id', auth()->id())
            ->with('images')
            ->firstOrFail();

        // Total Pendapatan dari pemesanan yang sudah bayar
        $totalPendapatan = \App\Models\Pemesanan::where('id_properti', $id)
            ->where('status_pembayaran', 'sudah_bayar')
            ->sum('total_harga');

        // Jumlah Penyewa Unik
        $jumlahPenyewa = \App\Models\Pemesanan::where('id_properti', $id)
            ->distinct('id_akun')
            ->count('id_akun');

        // Rating dan Review
        $reviews = \App\Models\Review::where('id_properti', $id)->get();
        $totalReviews = $reviews->count();
        $ratingRataRata = $totalReviews > 0 ? $reviews->avg('rating') : 0;

        // Rating Distribution
        $ratingDistribution = [];
        for ($i = 5; $i >= 1; $i--) {
            $count = $reviews->where('rating', $i)->count();
            $percentage = $totalReviews > 0 ? ($count / $totalReviews) * 100 : 0;
            $ratingDistribution[] = [
                'rating' => $i,
                'count' => $count,
                'percentage' => round($percentage, 1)
            ];
        }

        // Recent Reviews (10 terbaru dengan relasi penyewa)
        $recentReviews = \App\Models\Review::where('id_properti', $id)
            ->with('penyewa')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Hari Disewa (total lama_sewa dari semua pemesanan)
        $hariDisewa = \App\Models\Pemesanan::where('id_properti', $id)
            ->sum('lama_sewa');

        return view('pemilik.properti.show', compact(
            'properti',
            'totalPendapatan',
            'jumlahPenyewa',
            'ratingRataRata',
            'totalReviews',
            'ratingDistribution',
            'recentReviews',
            'hariDisewa'
        ));
    }

}