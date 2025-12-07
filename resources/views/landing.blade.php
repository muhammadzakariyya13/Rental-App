{{-- resources/views/landing.blade.php --}}
@extends('layouts.landing')

@section('title', 'Selamat Datang - Rental App')

@section('content')
<div class="min-h-screen bg-gray-100">

    {{-- Hero Section --}}
    <section class="mt-0 mb-0">
        <div class="relative px-12 md:px-20 lg:px-32 py-20 md:py-32 lg:py-40 text-center text-white overflow-hidden">
            {{-- Background Image --}}
            <div class="absolute inset-0 z-0">
                <img src="{{ asset('img/bg.jpg') }}" alt="Background" class="w-full h-full object-cover">
                {{-- Overlay for better text readability --}}
                <div class="absolute inset-0 bg-black bg-opacity-50"></div>
            </div>
            
            {{-- Content --}}
            <div class="relative z-10 max-w-5xl mx-auto">
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold flex flex-col md:flex-row items-center justify-center gap-5 mb-6">
                    <span>Selamat Datang di Rental App</span>
                </h1>
                <p class="text-lg md:text-xl lg:text-2xl leading-relaxed">
                    Temukan properti impian anda dari berbagai pilihan terbaik
                </p>
            </div>
        </div>
    </section>

    {{-- Properti Tersedia Section --}}
    <section class="px-4 md:px-8 lg:px-12 py-4 bg-white">
        <div class="mb-6 max-w-7xl mx-auto">
            <h2 class="text-2xl md:text-3xl font-bold text-gray-800 text-center mb-2">
                Properti Tersedia
            </h2>
            <p class="text-gray-600 text-center text-sm">
                {{ $properti->count() }} properti pilihan terbaik
            </p>
        </div>

        @if($properti->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 max-w-7xl mx-auto">
                @foreach($properti as $item)
                    <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition-shadow duration-300 border border-gray-200">
                        {{-- Image --}}
                        <div class="relative h-48 bg-gray-200">
                            @if($item->gambar)
                                <img src="{{ asset($item->gambar) }}" 
                                     alt="{{ $item->nama }}"
                                     class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-sky-100 to-sky-200">
                                    <span class="text-6xl">🏠</span>
                                </div>
                            @endif
                            
                            {{-- Badge Status --}}
                            <div class="absolute top-3 right-3">
                                @if($item->status == 'tersedia')
                                    <span class="bg-green-500 text-white text-xs px-3 py-1 rounded-full font-medium">
                                        ✓ Tersedia
                                    </span>
                                @else
                                    <span class="bg-red-500 text-white text-xs px-3 py-1 rounded-full font-medium">
                                        🔒 Sedang Disewa
                                    </span>
                                @endif
                            </div>
                        </div>

                        {{-- Content --}}
                        <div class="p-5">
                            <h3 class="text-lg font-semibold text-gray-800 mb-2 line-clamp-1">
                                {{ $item->nama }}
                            </h3>
                            
                            <p class="text-sm text-gray-600 mb-3 line-clamp-2">
                                📍 {{ $item->alamat }}
                            </p>

                            <div class="flex items-center justify-between mb-4">
                                <div class="text-sky-700 font-bold text-xl">
                                    Rp {{ number_format($item->harga, 0, ',', '.') }}
                                    <span class="text-sm text-gray-500 font-normal">/bulan</span>
                                </div>
                            </div>

                            <div class="flex gap-4 text-sm text-gray-600 mb-4">
                                <span>🛏️ {{ $item->kamar_tidur ?? 0 }} Kamar</span>
                                <span>🚿 {{ $item->kamar_mandi ?? 0 }} KM</span>
                            </div>

                            <a href="{{ route('login') }}" 
                               class="block w-full {{ $item->status == 'tersedia' ? 'bg-sky-700 hover:bg-sky-800' : 'bg-gray-500 hover:bg-gray-600' }} text-white text-center py-3 rounded-lg transition text-sm font-medium">
                                {{ $item->status == 'tersedia' ? 'Lihat Detail & Pesan' : 'Lihat Detail' }}
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Button Lihat Semua --}}
            <div class="mt-8 flex justify-center">
                <a href="{{ route('login') }}" 
                   class="bg-black hover:bg-sky-800 text-white px-8 py-3 rounded-lg transition-colors duration-300 flex items-center gap-2 shadow-md hover:shadow-lg font-medium">
                    <span>Lihat Semua Properti</span>
                </a>
            </div>
        @else
            <div class="text-center py-12">
                <div class="text-7xl mb-6">🏠</div>
                <p class="text-gray-600 text-lg">Belum ada properti tersedia saat ini</p>
            </div>
        @endif
    </section>

    {{-- Footer --}}
    <footer class="bg-white border-t mt-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                {{-- Logo & Description --}}
                <div>
                    <div class="flex items-center gap-2 mb-4">
                        <img src="{{ asset('img/rentalapp.png') }}" alt="Rental App Logo" class="w-10 h-10 object-contain">
                        <span class="text-xl font-bold text-gray-800">Rental App</span>
                    </div>
                    <p class="text-sm text-gray-600">
                        Platform rental properti terpercaya untuk kebutuhan hunian anda
                    </p>
                </div>

                {{-- Quick Links --}}
                <div>
                    <h3 class="text-sm font-semibold text-gray-800 mb-3">Tautan Cepat</h3>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('landing') }}" class="text-gray-600 hover:text-sky-700">Home</a></li>
                        <li><a href="#" class="text-gray-600 hover:text-sky-700">Tentang Kami</a></li>
                        <li><a href="#" class="text-gray-600 hover:text-sky-700">Properti</a></li>
                        <li><a href="#" class="text-gray-600 hover:text-sky-700">Kontak</a></li>
                    </ul>
                </div>

                {{-- Contact --}}
                <div>
                    <h3 class="text-sm font-semibold text-gray-800 mb-3">Hubungi Kami</h3>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="text-gray-600 hover:text-sky-700">Email: info@rentalapp.com</a></li>
                        <li><a href="#" class="text-gray-600 hover:text-sky-700">Telepon: +62 123 456 789</a></li>
                        <li><a href="#" class="text-gray-600 hover:text-sky-700">Alamat: Jakarta, Indonesia</a></li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-200 mt-8 pt-6 text-center text-sm text-gray-600">
                © {{ date('Y') }} Rental App. All rights reserved.
            </div>
        </div>
    </footer>
</div>
@endsection
