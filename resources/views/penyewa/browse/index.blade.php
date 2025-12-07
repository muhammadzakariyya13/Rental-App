@extends('layouts.penyewa')

@section('title', 'Browse Properti')

@section('content')
<div class="min-h-screen bg-gray-50">

    {{-- Header Section --}}
    <section class="bg-white border-b py-6 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 md:px-8">
            <h1 class="text-3xl font-bold text-sky-700 mb-6">Browse Properti</h1>
            
            {{-- Search & Filter --}}
            <form method="GET" action="{{ route('penyewa.browse') }}" class="flex flex-col md:flex-row gap-3">
                <select name="tipe" class="px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent bg-white">
                    <option value="">Semua Tipe</option>
                    <option value="rumah" {{ request('tipe') == 'rumah' ? 'selected' : '' }}>Rumah</option>
                    <option value="apartemen" {{ request('tipe') == 'apartemen' ? 'selected' : '' }}>Apartemen</option>
                    <option value="vila" {{ request('tipe') == 'vila' ? 'selected' : '' }}>Vila</option>
                    <option value="kontrakan" {{ request('tipe') == 'kontrakan' ? 'selected' : '' }}>Kontrakan</option>
                </select>
                
                <select name="status" class="px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent bg-white">
                    <option value="">Semua Status</option>
                    <option value="tersedia" {{ request('status') == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                    <option value="disewa" {{ request('status') == 'disewa' ? 'selected' : '' }}>Disewa</option>
                </select>
                
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}"
                       placeholder="Cari berdasarkan nama atau lokasi..."
                       class="flex-1 px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent">
                
                <button type="submit" class="bg-sky-600 hover:bg-sky-700 text-white px-8 py-2.5 rounded-lg transition font-medium shadow-sm">
                    🔍 Cari
                </button>
            </form>
        </div>
    </section>

    {{-- Properti Grid Section --}}
    <section class="max-w-7xl mx-auto px-4 md:px-8 py-8">
        @if($properti->count() > 0)
            <div class="mb-4 text-gray-600">
                Menampilkan {{ $properti->count() }} dari {{ $properti->total() }} properti
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($properti as $item)
                    <div class="bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100">
                        {{-- Image --}}
                        <div class="relative h-56 bg-gradient-to-br from-gray-100 to-gray-200">
                            @if($item->gambar)
                                <img src="data:image/jpeg;base64,{{ $item->gambar }}" 
                                     alt="{{ $item->nama }}"
                                     class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-sky-50 to-sky-100">
                                    <span class="text-6xl">🏠</span>
                                </div>
                            @endif
                            
                            {{-- Tipe Badge --}}
                            <div class="absolute top-3 left-3">
                                <span class="bg-sky-600 text-white text-xs font-semibold px-3 py-1 rounded-full shadow-lg uppercase">
                                    {{ ucfirst($item->tipe ?? 'Properti') }}
                                </span>
                            </div>
                            
                            {{-- Status Badge --}}
                            <div class="absolute top-3 right-3">
                                @if($item->status == 'tersedia')
                                    <span class="bg-green-500 text-white text-xs font-semibold px-3 py-1 rounded-full shadow-lg">
                                        ✓ Tersedia
                                    </span>
                                @else
                                    <span class="bg-red-500 text-white text-xs font-semibold px-3 py-1 rounded-full shadow-lg">
                                        🔒 Disewa
                                    </span>
                                @endif
                            </div>
                        </div>

                        {{-- Content --}}
                        <div class="p-5">
                            <h3 class="text-lg font-bold text-gray-900 mb-2 line-clamp-2" style="min-height: 56px;">
                                {{ $item->nama }}
                            </h3>
                            
                            <div class="flex items-start mb-3 text-sm text-gray-600">
                                <svg class="w-4 h-4 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <span class="line-clamp-2">{{ $item->alamat }}</span>
                            </div>
                            
                            <p class="text-sm text-gray-600 mb-4 line-clamp-3" style="min-height: 63px;">
                                {{ Str::limit($item->deskripsi, 120) }}
                            </p>

                            {{-- Spesifikasi --}}
                            <div class="flex items-center gap-4 mb-4 pb-4 border-b text-sm text-gray-600">
                                @if($item->kamar_tidur)
                                    <div class="flex items-center">
                                        <span class="mr-1">🛏️</span>
                                        <span>{{ $item->kamar_tidur }} KT</span>
                                    </div>
                                @endif
                                @if($item->kamar_mandi)
                                    <div class="flex items-center">
                                        <span class="mr-1">🚿</span>
                                        <span>{{ $item->kamar_mandi }} KM</span>
                                    </div>
                                @endif
                                @if($item->luas_bangunan)
                                    <div class="flex items-center">
                                        <span class="mr-1">📐</span>
                                        <span>{{ $item->luas_bangunan }} m²</span>
                                    </div>
                                @endif
                            </div>

                            <div class="mb-4">
                                <div class="text-sky-700 font-bold text-2xl">
                                    Rp {{ number_format($item->harga, 0, ',', '.') }}
                                </div>
                                <div class="text-xs text-gray-500 mt-1">per bulan</div>
                            </div>

                            {{-- Buttons --}}
                            <div class="flex gap-2">
                                <a href="{{ route('penyewa.browse.show', $item->id_properti) }}" 
                                   class="flex-1 bg-sky-600 hover:bg-sky-700 text-white text-center py-2.5 rounded-lg text-sm font-medium transition shadow-sm">
                                    📋 Detail
                                </a>
                                @if($item->status == 'tersedia')
                                    <a href="{{ route('penyewa.pemesanan.create', $item->id_properti) }}" 
                                       class="flex-1 bg-green-500 hover:bg-green-600 text-white text-center py-2.5 rounded-lg text-sm font-medium transition shadow-sm">
                                        📩 Pesan
                                    </a>
                                @else
                                    <button disabled
                                       class="flex-1 bg-gray-400 text-white text-center py-2.5 rounded-lg text-sm font-medium cursor-not-allowed opacity-60">
                                        🔒 Sedang Disewa
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            @if($properti->hasPages())
                <div class="mt-8 flex justify-center">
                    {{ $properti->links() }}
                </div>
            @endif
        @else
            <div class="text-center py-16">
                <div class="text-8xl mb-4">🔍</div>
                <h3 class="text-2xl font-bold text-gray-700 mb-2">Properti tidak ditemukan</h3>
                <p class="text-gray-500 mb-6">Coba gunakan kata kunci pencarian yang berbeda</p>
                <a href="{{ route('penyewa.browse') }}" class="inline-block bg-sky-600 hover:bg-sky-700 text-white px-6 py-3 rounded-lg transition font-medium">
                    🔄 Reset Pencarian
                </a>
            </div>
        @endif
    </section>

</div>
@endsection
