@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <!-- Breadcrumb -->
    <nav class="flex mb-6" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('properti.index') }}" class="text-blue-600 hover:text-blue-800">
                    🏠 Properti
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="ml-1 text-gray-500 md:ml-2">{{ $properti->nama }}</span>
                </div>
            </li>
        </ol>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2">
            <!-- Property Image -->
            <div class="mb-6">
                @if($properti->foto)
                    <img src="{{ asset('storage/' . $properti->foto) }}" alt="{{ $properti->nama }}" 
                         class="w-full h-96 object-cover rounded-lg">
                @else
                    <div class="w-full h-96 bg-gradient-to-br from-blue-100 to-blue-200 flex items-center justify-center rounded-lg">
                        <span class="text-blue-600 text-8xl">🏠</span>
                    </div>
                @endif
            </div>

            <!-- Property Info -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <div class="flex justify-between items-start mb-4">
                    <h1 class="text-3xl font-bold text-gray-800">{{ $properti->nama }}</h1>
                    <span class="px-3 py-1 text-sm rounded-full {{ $properti->status == 'tersedia' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ ucfirst($properti->status) }}
                    </span>
                </div>

                <div class="text-3xl font-bold text-blue-600 mb-6">
                    Rp {{ number_format($properti->harga, 0, ',', '.') }}
                    <span class="text-lg text-gray-500 font-normal">/bulan</span>
                </div>

                <div class="prose max-w-none">
                    <h3 class="text-lg font-semibold mb-3">Deskripsi Properti</h3>
                    <p class="text-gray-700 leading-relaxed">{{ $properti->deskripsi }}</p>
                </div>
            </div>

            <!-- Features -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold mb-4">Fitur & Fasilitas</h3>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    <div class="flex items-center text-gray-700">
                        <span class="mr-2">🛏️</span> Kamar Tidur
                    </div>
                    <div class="flex items-center text-gray-700">
                        <span class="mr-2">🚿</span> Kamar Mandi
                    </div>
                    <div class="flex items-center text-gray-700">
                        <span class="mr-2">🅿️</span> Parkir
                    </div>
                    <div class="flex items-center text-gray-700">
                        <span class="mr-2">💡</span> Listrik
                    </div>
                    <div class="flex items-center text-gray-700">
                        <span class="mr-2">💧</span> Air
                    </div>
                    <div class="flex items-center text-gray-700">
                        <span class="mr-2">📶</span> Internet
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-md p-6 sticky top-6">
                <h3 class="text-lg font-semibold mb-4">Booking Properti</h3>
                
                @if($properti->status == 'tersedia')
                    @auth
                        <div class="space-y-4">
                            <div class="p-4 bg-green-50 border border-green-200 rounded-lg">
                                <div class="flex items-center">
                                    <span class="text-green-600 text-2xl mr-2">✅</span>
                                    <div>
                                        <p class="text-green-800 font-medium">Properti Tersedia</p>
                                        <p class="text-green-600 text-sm">Siap untuk disewa</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="border-t pt-4">
                                <div class="text-lg font-semibold text-gray-800 mb-2">Harga Sewa</div>
                                <div class="text-2xl font-bold text-blue-600">
                                    Rp {{ number_format($properti->harga, 0, ',', '.') }}
                                    <span class="text-sm text-gray-500">/bulan</span>
                                </div>
                            </div>

                            @auth
                                <a href="{{ route('properti.book', $properti->id_properti) }}" 
                                   class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg hover:bg-blue-700 transition duration-200 font-medium text-center block">
                                    🛒 Sewa Sekarang
                                </a>
                            @else
                                <a href="{{ route('login') }}" 
                                   class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg hover:bg-blue-700 transition duration-200 font-medium text-center block">
                                    🔐 Login untuk Sewa
                                </a>
                            @endauth
                            
                            @auth
                                <a href="{{ route('reviews.create', $properti->id_properti) }}" 
                                   class="w-full bg-gray-600 text-white py-2 px-4 rounded-lg hover:bg-gray-700 transition duration-200 text-center block">
                                    ⭐ Tulis Review
                                </a>
                            @endauth
                        </div>
                    @else
                        <div class="space-y-4">
                            <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                                <div class="flex items-center">
                                    <span class="text-yellow-600 text-2xl mr-2">🔐</span>
                                    <div>
                                        <p class="text-yellow-800 font-medium">Login Required</p>
                                        <p class="text-yellow-600 text-sm">Silakan login untuk menyewa properti</p>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-2">
                                <a href="{{ route('login') }}" 
                                   class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg hover:bg-blue-700 transition duration-200 font-medium text-center block">
                                    Login
                                </a>
                                <a href="{{ route('register') }}" 
                                   class="w-full bg-gray-600 text-white py-3 px-4 rounded-lg hover:bg-gray-700 transition duration-200 font-medium text-center block">
                                    Daftar Akun
                                </a>
                            </div>
                        </div>
                    @endauth
                @else
                    <div class="space-y-4">
                        <div class="p-4 bg-red-50 border border-red-200 rounded-lg">
                            <div class="flex items-center">
                                <span class="text-red-600 text-2xl mr-2">❌</span>
                                <div>
                                    <p class="text-red-800 font-medium">Tidak Tersedia</p>
                                    <p class="text-red-600 text-sm">Properti sedang disewa</p>
                                </div>
                            </div>
                        </div>
                        
                        <button disabled 
                                class="w-full bg-gray-300 text-gray-500 py-3 px-4 rounded-lg cursor-not-allowed font-medium">
                            Tidak Dapat Disewa
                        </button>
                    </div>
                @endif

                <!-- Contact Info -->
                <div class="mt-6 pt-6 border-t">
                    <h4 class="font-semibold mb-3">Butuh Bantuan?</h4>
                    <div class="space-y-2 text-sm text-gray-600">
                        <div class="flex items-center">
                            <span class="mr-2">📞</span>
                            <span>+62 821-xxxx-xxxx</span>
                        </div>
                        <div class="flex items-center">
                            <span class="mr-2">✉️</span>
                            <span>info@rental.com</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
