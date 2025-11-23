@extends('layouts.app')

@section('content')
<div class="container mx-auto">
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 text-white rounded-lg mb-8">
        <div class="px-8 py-12 text-center">
            <h1 class="text-4xl font-bold mb-4">🏠 Selamat Datang di Rental App</h1>
            <p class="text-xl mb-6">Platform rental properti terpercaya untuk kebutuhan hunian Anda</p>
            @guest
                <div class="space-x-4">
                    <a href="{{ route('login') }}" class="bg-white text-blue-600 px-6 py-3 rounded-lg font-medium hover:bg-gray-100">
                        Masuk
                    </a>
                    <a href="{{ route('register') }}" class="bg-blue-500 text-white px-6 py-3 rounded-lg font-medium hover:bg-blue-400 border border-blue-400">
                        Daftar Sekarang
                    </a>
                </div>
            @endguest
        </div>
    </div>

    <!-- Featured Properties -->
    <div class="mb-8">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">🌟 Properti Unggulan</h2>
            <a href="{{ route('properti.index') }}" class="text-blue-600 hover:text-blue-800 font-medium">
                Lihat Semua →
            </a>
        </div>

        @if(isset($propertiUnggulan) && $propertiUnggulan->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($propertiUnggulan as $properti)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition duration-300">
                        @if($properti->foto)
                            <img src="{{ asset('storage/' . $properti->foto) }}" alt="{{ $properti->nama }}" class="w-full h-48 object-cover">
                        @else
                            <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                <span class="text-gray-500 text-4xl">🏠</span>
                            </div>
                        @endif
                        <div class="p-6">
                            <h3 class="text-xl font-semibold text-gray-800 mb-2">{{ $properti->nama }}</h3>
                            <p class="text-gray-600 mb-2">📍 {{ $properti->lokasi }}</p>
                            <p class="text-2xl font-bold text-blue-600 mb-4">
                                Rp {{ number_format($properti->harga, 0, ',', '.') }}/bulan
                            </p>
                            <a href="{{ route('properti.show', $properti->id_properti) }}" 
                               class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 transition duration-200 block text-center">
                                Lihat Detail
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12">
                <div class="text-6xl mb-4">🏠</div>
                <h3 class="text-xl font-semibold text-gray-800 mb-2">Belum Ada Properti</h3>
                <p class="text-gray-600">Properti unggulan akan ditampilkan di sini</p>
            </div>
        @endif
    </div>

    <!-- Features Section -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
        <div class="text-center p-6 bg-white rounded-lg shadow-md">
            <div class="text-4xl mb-4">🔍</div>
            <h3 class="text-xl font-semibold mb-2">Cari & Filter</h3>
            <p class="text-gray-600">Temukan properti sesuai kebutuhan dengan fitur pencarian dan filter yang lengkap</p>
        </div>
        <div class="text-center p-6 bg-white rounded-lg shadow-md">
            <div class="text-4xl mb-4">💳</div>
            <h3 class="text-xl font-semibold mb-2">Pembayaran Mudah</h3>
            <p class="text-gray-600">Sistem pembayaran terintegrasi dengan berbagai metode pembayaran yang aman</p>
        </div>
        <div class="text-center p-6 bg-white rounded-lg shadow-md">
            <div class="text-4xl mb-4">📋</div>
            <h3 class="text-xl font-semibold mb-2">Kelola Mudah</h3>
            <p class="text-gray-600">Dashboard lengkap untuk mengelola pemesanan, kontrak, dan pembayaran</p>
        </div>
    </div>

    @auth
        <!-- Quick Actions for Logged Users -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-xl font-semibold mb-4">Quick Actions</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <a href="{{ route('properti.index') }}" class="bg-blue-50 hover:bg-blue-100 p-4 rounded-lg text-center transition duration-200">
                    <div class="text-2xl mb-2">🏠</div>
                    <div class="font-medium text-blue-600">Browse Properti</div>
                </a>
                
                @if(auth()->user()->role && auth()->user()->role->nama === 'user')
                    <a href="{{ route('user.pemesanan') }}" class="bg-green-50 hover:bg-green-100 p-4 rounded-lg text-center transition duration-200">
                        <div class="text-2xl mb-2">📝</div>
                        <div class="font-medium text-green-600">Pemesanan Saya</div>
                    </a>
                    <a href="{{ route('user.kontrak') }}" class="bg-purple-50 hover:bg-purple-100 p-4 rounded-lg text-center transition duration-200">
                        <div class="text-2xl mb-2">📄</div>
                        <div class="font-medium text-purple-600">Kontrak Saya</div>
                    </a>
                    <a href="{{ route('payment.history') }}" class="bg-yellow-50 hover:bg-yellow-100 p-4 rounded-lg text-center transition duration-200">
                        <div class="text-2xl mb-2">💳</div>
                        <div class="font-medium text-yellow-600">Riwayat Pembayaran</div>
                    </a>
                @endif

                @if(auth()->user()->role && auth()->user()->role->nama === 'admin')
                    <a href="{{ route('admin.dashboard') }}" class="bg-red-50 hover:bg-red-100 p-4 rounded-lg text-center transition duration-200">
                        <div class="text-2xl mb-2">⚙️</div>
                        <div class="font-medium text-red-600">Admin Dashboard</div>
                    </a>
                    <a href="{{ route('admin.properti.index') }}" class="bg-indigo-50 hover:bg-indigo-100 p-4 rounded-lg text-center transition duration-200">
                        <div class="text-2xl mb-2">🏢</div>
                        <div class="font-medium text-indigo-600">Kelola Properti</div>
                    </a>
                    <a href="{{ route('admin.payments.index') }}" class="bg-pink-50 hover:bg-pink-100 p-4 rounded-lg text-center transition duration-200">
                        <div class="text-2xl mb-2">💰</div>
                        <div class="font-medium text-pink-600">Monitor Pembayaran</div>
                    </a>
                @endif
            </div>
        </div>
    @endauth
</div>
@endsection