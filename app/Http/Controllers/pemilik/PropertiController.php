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
        ]);

        $validated['pemilik_id'] = auth()->id();
        $validated['status'] = 'tersedia';

        Properti::create($validated);

        if ($request->hasFile('images')) {
            $this->handleImageUpload($request, $properti);
        }

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
            'status' => 'required|in:tersedia,disewa,maintenance',
        ]);

        $properti->update($validated);

        if ($request->hasFile('images')) {
            $this->handleImageUpload($request, $properti);
        }

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
        $properti = Properti::with(['images', 'reviews.penyewa'])
            ->where('id_pemilik', Auth::id())
            ->findOrFail($id);

        // Get statistics
        $totalPendapatan = $properti->totalPendapatan();
        $jumlahPenyewa = $properti->jumlahPenyewa();
        $ratingRataRata = $properti->averageRating();
        $totalReviews = $properti->totalReviews();
        $ratingDistribution = $properti->ratingDistribution();
        $recentReviews = $properti->recentReviews();

        return view('pemilik.properti.show', compact(
            'properti',
            'totalPendapatan',
            'jumlahPenyewa', 
            'ratingRataRata',
            'totalReviews',
            'ratingDistribution',
            'recentReviews'
        ));
    }
    
    private function handleImageUpload(Request $request, Properti $properti)
    {
        $images = $request->file('images');
        $currentMaxOrder = $properti->images()->max('order_index') ?? -1;

        foreach ($images as $index => $image) {
            $imageName = Str::random(10) . '_' . time() . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('properti/' . $properti->id_properti, $imageName, 'public');

            $isFirst = $properti->images()->count() === 0 && $index === 0;

            PropertiImage::create([
                'id_properti' => $properti->id_properti,
                'image_path' => $imagePath,
                'image_name' => $imageName,
                'is_primary' => $isFirst,
                'order_index' => $currentMaxOrder + $index + 1
            ]);
        }
    }

    public function deleteImage($propertiId, $imageId)
    {
        $properti = Properti::where('pemilik_id', auth()->id())->findOrFail($propertiId);
        $image = PropertiImage::where('id_properti', $properti->id_properti)->findOrFail($imageId);

        if (Storage::exists($image->image_path)) {
            Storage::delete($image->image_path);
        }

        $image->delete();

        return response()->json(['success' => true]);
    }

    public function setPrimaryImage($propertiId, $imageId)
    {
        $properti = Properti::where('pemilik_id', auth()->id())->findOrFail($propertiId);
        
        PropertiImage::where('id_properti', $properti->id_properti)
            ->update(['is_primary' => false]);

        PropertiImage::where('id_properti', $properti->id_properti)
            ->where('id', $imageId)
            ->update(['is_primary' => true]);

        return response()->json(['success' => true]);
    }
}