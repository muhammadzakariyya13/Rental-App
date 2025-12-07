@extends('layouts.penyewa')

@section('title', $properti->nama)

@section('content')
<div class="min-h-screen bg-gray-50">

    {{-- Breadcrumb --}}
    <div class="bg-white border-b py-4">
        <div class="max-w-7xl mx-auto px-4 md:px-8">
            <div class="flex items-center gap-2 text-sm text-gray-600">
                <a href="{{ route('penyewa.dashboard') }}" class="hover:text-sky-600">Home</a>
                <span>/</span>
                <a href="{{ route('penyewa.browse') }}" class="hover:text-sky-600">Browse Properti</a>
                <span>/</span>
                <span class="text-gray-900 font-medium">{{ $properti->nama }}</span>
            </div>
        </div>
    </div>

    {{-- Main Content --}}
    <div class="max-w-7xl mx-auto px-4 md:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            {{-- Left Content --}}
            <div class="lg:col-span-2 space-y-6">
                
                {{-- Image Section --}}
                <div class="bg-white rounded-xl shadow-md overflow-hidden">
                    @if($properti->gambar)
                        <img src="data:image/jpeg;base64,{{ $properti->gambar }}" 
                             alt="{{ $properti->nama }}"
                             class="w-full h-96 object-cover">
                    @else
                        <div class="w-full h-96 flex items-center justify-center bg-gradient-to-br from-sky-50 to-sky-100">
                            <span class="text-9xl">🏠</span>
                        </div>
                    @endif
                </div>

                {{-- Property Info Card --}}
                <div class="bg-white rounded-xl shadow-md p-6">
                    {{-- Header --}}
                    <div class="mb-6">
                        <div class="flex items-start justify-between mb-3">
                            <div>
                                <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $properti->nama }}</h1>
                                <div class="flex items-center gap-2 text-gray-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    <span>{{ $properti->alamat }}</span>
                                </div>
                            </div>
                            <span class="px-4 py-2 bg-sky-100 text-sky-700 font-semibold rounded-full text-sm uppercase">
                                {{ ucfirst($properti->tipe ?? 'Properti') }}
                            </span>
                        </div>

                        {{-- Status Badge --}}
                        @if($properti->status == 'tersedia')
                            <span class="inline-flex items-center gap-1 px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm font-medium">
                                <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                                Tersedia
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 px-3 py-1 bg-red-100 text-red-700 rounded-full text-sm font-medium">
                                <span class="w-2 h-2 bg-red-500 rounded-full"></span>
                                Sedang Disewa
                            </span>
                        @endif
                    </div>

                    {{-- Specifications Grid --}}
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6 pb-6 border-b">
                        @if($properti->kamar_tidur)
                        <div class="text-center p-4 bg-gray-50 rounded-lg">
                            <div class="text-3xl mb-2">🛏️</div>
                            <div class="text-2xl font-bold text-gray-900">{{ $properti->kamar_tidur }}</div>
                            <div class="text-sm text-gray-600">Kamar Tidur</div>
                        </div>
                        @endif

                        @if($properti->kamar_mandi)
                        <div class="text-center p-4 bg-gray-50 rounded-lg">
                            <div class="text-3xl mb-2">🚿</div>
                            <div class="text-2xl font-bold text-gray-900">{{ $properti->kamar_mandi }}</div>
                            <div class="text-sm text-gray-600">Kamar Mandi</div>
                        </div>
                        @endif

                        @if($properti->luas_tanah)
                        <div class="text-center p-4 bg-gray-50 rounded-lg">
                            <div class="text-3xl mb-2">🌳</div>
                            <div class="text-2xl font-bold text-gray-900">{{ $properti->luas_tanah }}</div>
                            <div class="text-sm text-gray-600">Luas Tanah (m²)</div>
                        </div>
                        @endif

                        @if($properti->luas_bangunan)
                        <div class="text-center p-4 bg-gray-50 rounded-lg">
                            <div class="text-3xl mb-2">🏗️</div>
                            <div class="text-2xl font-bold text-gray-900">{{ $properti->luas_bangunan }}</div>
                            <div class="text-sm text-gray-600">Luas Bangunan (m²)</div>
                        </div>
                        @endif
                    </div>

                    {{-- Description --}}
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">📝 Deskripsi Properti</h3>
                        <p class="text-gray-700 leading-relaxed whitespace-pre-line">{{ $properti->deskripsi }}</p>
                    </div>
                </div>

                {{-- Reviews Section --}}
                @if($reviews && $reviews->count() > 0)
                <div class="bg-white rounded-xl shadow-md p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-2xl font-bold text-gray-900">⭐ Ulasan Penyewa</h3>
                        <div class="text-right">
                            <div class="flex items-center gap-2">
                                <span class="text-3xl font-bold text-yellow-500">{{ number_format($reviews->avg('rating'), 1) }}</span>
                                <div>
                                    <div class="flex text-yellow-400 text-lg">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= round($reviews->avg('rating')))
                                                ★
                                            @else
                                                ☆
                                            @endif
                                        @endfor
                                    </div>
                                    <p class="text-xs text-gray-500">{{ $reviews->count() }} ulasan</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="space-y-6">
                        @foreach($reviews as $review)
                        <div class="pb-6 border-b border-gray-200 last:border-0 last:pb-0">
                            <div class="flex items-start gap-4">
                                {{-- Avatar --}}
                                <div class="flex-shrink-0">
                                    @if($review->penyewa && $review->penyewa->profile_photo)
                                        @php
                                            $profilePhoto = $review->penyewa->profile_photo;
                                            // Cek apakah sudah base64 atau path file
                                            if (str_starts_with($profilePhoto, 'data:image')) {
                                                $profileSrc = $profilePhoto;
                                            } elseif (str_contains($profilePhoto, 'base64,')) {
                                                $profileSrc = 'data:image/jpeg;base64,' . $profilePhoto;
                                            } else {
                                                // Path file, gunakan storage URL
                                                $profileSrc = asset('storage/' . $profilePhoto);
                                            }
                                        @endphp
                                        <img src="{{ $profileSrc }}" alt="{{ $review->penyewa->username }}" class="w-12 h-12 rounded-full object-cover shadow-md">
                                    @else
                                        <div class="w-12 h-12 bg-gradient-to-br from-sky-400 to-sky-600 rounded-full flex items-center justify-center shadow-md">
                                            <span class="text-white font-bold text-lg">{{ substr($review->penyewa->username ?? 'U', 0, 1) }}</span>
                                        </div>
                                    @endif
                                </div>
                                
                                {{-- Content --}}
                                <div class="flex-1">
                                    <div class="flex items-start justify-between mb-2">
                                        <div>
                                            <p class="font-bold text-gray-900">{{ $review->penyewa->username ?? 'User' }}</p>
                                            <p class="text-sm text-gray-500">{{ $review->tanggal_review ? $review->tanggal_review->format('d M Y') : $review->created_at->format('d M Y') }}</p>
                                        </div>
                                        <div class="flex items-center gap-1 bg-yellow-50 px-3 py-1 rounded-full">
                                            <span class="text-yellow-500 font-bold">{{ $review->rating }}</span>
                                            <span class="text-yellow-400">★</span>
                                        </div>
                                    </div>
                                    
                                    <p class="text-gray-700 leading-relaxed mb-3">{{ $review->review }}</p>
                                    
                                    {{-- Reply from Owner --}}
                                    @if($review->pemilik_reply)
                                    <div class="mt-4 pl-4 border-l-3 border-sky-400 bg-sky-50 p-4 rounded-r-lg">
                                        <div class="flex items-start gap-3 mb-2">
                                            {{-- Owner Avatar --}}
                                            @if($properti->pemilik && $properti->pemilik->profile_photo)
                                                @php
                                                    $ownerPhoto = $properti->pemilik->profile_photo;
                                                    if (str_starts_with($ownerPhoto, 'data:image')) {
                                                        $ownerSrc = $ownerPhoto;
                                                    } elseif (str_contains($ownerPhoto, 'base64,')) {
                                                        $ownerSrc = 'data:image/jpeg;base64,' . $ownerPhoto;
                                                    } else {
                                                        $ownerSrc = asset('storage/' . $ownerPhoto);
                                                    }
                                                @endphp
                                                <img src="{{ $ownerSrc }}" alt="Pemilik" class="w-8 h-8 rounded-full object-cover shadow-sm">
                                            @else
                                                <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center shadow-sm">
                                                    <span class="text-white font-bold text-xs">{{ substr($properti->pemilik->username ?? 'P', 0, 1) }}</span>
                                                </div>
                                            @endif
                                            <div class="flex-1">
                                                <div class="flex items-center gap-2">
                                                    <svg class="w-5 h-5 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                                                    </svg>
                                                    <p class="text-sm font-bold text-sky-700">Balasan dari {{ $properti->pemilik->username ?? 'Pemilik' }}</p>
                                                </div>
                                            </div>
                                        </div>
                                        <p class="text-sm text-gray-700 leading-relaxed ml-11">{{ $review->pemilik_reply }}</p>
                                        @if($review->reply_date)
                                        <p class="text-xs text-gray-500 mt-2 ml-11">{{ $review->reply_date->format('d M Y') }}</p>
                                        @endif
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @else
                <div class="bg-white rounded-xl shadow-md p-8 text-center">
                    <div class="text-6xl mb-4">💬</div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Belum Ada Ulasan</h3>
                    <p class="text-gray-600">Jadilah yang pertama memberikan ulasan untuk properti ini</p>
                </div>
                @endif

            </div>

            {{-- Right Sidebar --}}
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-md p-6 sticky top-24">
                    
                    {{-- Price --}}
                    <div class="mb-6 pb-6 border-b">
                        <p class="text-sm text-gray-600 mb-1">Harga Sewa</p>
                        <div class="flex items-baseline gap-2">
                            <span class="text-3xl font-bold text-sky-600">Rp {{ number_format($properti->harga, 0, ',', '.') }}</span>
                            <span class="text-gray-500">/ bulan</span>
                        </div>
                    </div>

                    {{-- Buttons --}}
                    <div class="space-y-3">
                        @if($properti->status == 'tersedia')
                            <a href="{{ route('penyewa.pemesanan.create', $properti->id_properti) }}" class="block w-full bg-sky-600 hover:bg-sky-700 text-white py-3 rounded-lg font-semibold transition shadow-sm text-center">
                                📩 Pesan Sekarang
                            </a>
                        @else
                            <button disabled class="w-full bg-gray-400 text-white py-3 rounded-lg font-semibold cursor-not-allowed opacity-60">
                                🔒 Sedang Disewa
                            </button>
                        @endif
                    </div>

                    {{-- Pemilik Info --}}
                    @if($properti->pemilik)
                    <div class="mt-6 pt-6 border-t">
                        <h4 class="text-sm font-semibold text-gray-600 mb-3">Pemilik Properti</h4>
                        <div class="flex items-center gap-3 mb-4">
                            @if($properti->pemilik->profile_photo)
                                @php
                                    $ownerPhoto = $properti->pemilik->profile_photo;
                                    if (str_starts_with($ownerPhoto, 'data:image')) {
                                        $ownerSrc = $ownerPhoto;
                                    } elseif (str_contains($ownerPhoto, 'base64,')) {
                                        $ownerSrc = 'data:image/jpeg;base64,' . $ownerPhoto;
                                    } else {
                                        $ownerSrc = asset('storage/' . $ownerPhoto);
                                    }
                                @endphp
                                <img src="{{ $ownerSrc }}" alt="{{ $properti->pemilik->username }}" class="w-12 h-12 rounded-full object-cover shadow-md">
                            @else
                                <div class="w-12 h-12 bg-gradient-to-br from-sky-400 to-sky-600 rounded-full flex items-center justify-center text-white font-bold text-lg shadow-md">
                                    {{ substr($properti->pemilik->username ?? 'P', 0, 1) }}
                                </div>
                            @endif
                            <div>
                                <p class="font-semibold text-gray-900">{{ $properti->pemilik->username ?? 'Pemilik' }}</p>
                                <p class="text-sm text-gray-500">{{ $properti->pemilik->email ?? '' }}</p>
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- Additional Info --}}
                    <div class="mt-6 pt-6 border-t">
                        <div class="space-y-2 text-sm text-gray-600">
                            <div class="flex items-center gap-2">
                                <span>📅</span>
                                <span>Ditambahkan {{ $properti->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>

</div>
@endsection
