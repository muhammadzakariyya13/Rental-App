<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Rental App') }} - @yield('title', 'Penyewa')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-100">
    <div class="min-h-screen">
        <!-- Navigation -->
        <nav class="bg-white border-b border-gray-200 sticky top-0 z-50 shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <!-- Logo -->
                    <div class="flex items-center gap-3">
                        <a href="{{ route('penyewa.dashboard') }}" class="flex items-center gap-2">
                            <img src="{{ asset('img/rentalapp.png') }}" alt="Rental App Logo" class="w-10 h-10 object-contain">
                            <span class="text-xl font-bold text-gray-800">Rental App</span>
                        </a>
                    </div>

                    <!-- Navigation Links -->
                    <div class="hidden md:flex items-center gap-8">
                        <a href="{{ route('penyewa.browse') }}" 
                           class="text-gray-700 hover:text-sky-700 font-medium transition {{ request()->routeIs('penyewa.browse*') ? 'text-sky-700' : '' }}">
                            Browse Properti
                        </a>
                        <a href="{{ route('penyewa.pemesanan.index') }}" 
                           class="text-gray-700 hover:text-sky-700 font-medium transition {{ request()->routeIs('penyewa.pemesanan*') ? 'text-sky-700' : '' }}">
                            Pemesanan Saya
                        </a>
                        <a href="{{ route('penyewa.kontrak.index') }}" 
                           class="text-gray-700 hover:text-sky-700 font-medium transition {{ request()->routeIs('penyewa.kontrak*') ? 'text-sky-700' : '' }}">
                            Kontrak Saya
                        </a>
                        <a href="{{ route('penyewa.riwayat-pembayaran.index') }}" 
                           class="text-gray-700 hover:text-sky-700 font-medium transition {{ request()->routeIs('penyewa.riwayat-pembayaran*') ? 'text-sky-700' : '' }}">
                            Riwayat Pembayaran
                        </a>
                    </div>

                    <!-- User Dropdown -->
                    <div class="flex items-center gap-4">
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" 
                                    class="flex items-center gap-2 text-gray-700 hover:text-sky-700 font-medium transition">
                                @if(Auth::user()->profile_photo)
                                    <img src="{{ asset('storage/' . Auth::user()->profile_photo) }}" 
                                         alt="Profile Photo" 
                                         class="w-8 h-8 rounded-full object-cover border-2 border-gray-200">
                                @else
                                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-sky-400 to-blue-500 flex items-center justify-center text-white text-sm font-bold border-2 border-gray-200">
                                        {{ strtoupper(substr(Auth::user()->username, 0, 1)) }}
                                    </div>
                                @endif
                                <span>{{ Auth::user()->username }}</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>

                            <!-- Dropdown Menu -->
                            <div x-show="open" 
                                 @click.away="open = false"
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 scale-95"
                                 x-transition:enter-end="opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="opacity-100 scale-100"
                                 x-transition:leave-end="opacity-0 scale-95"
                                 class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 z-50"
                                 style="display: none;">
                                <a href="{{ route('profile.edit') }}" 
                                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Profile
                                </a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" 
                                            class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Mobile menu button -->
                    <div class="md:hidden">
                        <button @click="mobileMenuOpen = !mobileMenuOpen" 
                                class="text-gray-700 hover:text-sky-700">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Mobile Menu -->
                <div x-show="mobileMenuOpen" 
                     x-transition
                     class="md:hidden py-4 border-t"
                     style="display: none;"
                     x-data="{ mobileMenuOpen: false }">
                    <!-- User Info in Mobile -->
                    <div class="flex items-center gap-3 py-3 mb-3 border-b">
                        @if(Auth::user()->profile_photo)
                            <img src="{{ asset('storage/' . Auth::user()->profile_photo) }}" 
                                 alt="Profile Photo" 
                                 class="w-10 h-10 rounded-full object-cover border-2 border-gray-200">
                        @else
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-sky-400 to-blue-500 flex items-center justify-center text-white font-bold border-2 border-gray-200">
                                {{ strtoupper(substr(Auth::user()->username, 0, 1)) }}
                            </div>
                        @endif
                        <span class="font-medium text-gray-800">{{ Auth::user()->username }}</span>
                    </div>
                    
                    <a href="{{ route('penyewa.dashboard') }}" 
                       class="block py-2 text-gray-700 hover:text-sky-700 {{ request()->routeIs('penyewa.dashboard') ? 'text-sky-700 font-semibold' : '' }}">
                        Dashboard
                    </a>
                    <a href="{{ route('penyewa.browse') }}" 
                       class="block py-2 text-gray-700 hover:text-sky-700 {{ request()->routeIs('penyewa.browse*') ? 'text-sky-700 font-semibold' : '' }}">
                        Browse Properti
                    </a>
                    <a href="{{ route('penyewa.pemesanan.index') }}" 
                       class="block py-2 text-gray-700 hover:text-sky-700 {{ request()->routeIs('penyewa.pemesanan*') ? 'text-sky-700 font-semibold' : '' }}">
                        Pemesanan Saya
                    </a>
                    <a href="{{ route('penyewa.kontrak.index') }}" 
                       class="block py-2 text-gray-700 hover:text-sky-700 {{ request()->routeIs('penyewa.kontrak*') ? 'text-sky-700 font-semibold' : '' }}">
                        Kontrak Saya
                    </a>
                    <a href="{{ route('penyewa.riwayat-pembayaran.index') }}" 
                       class="block py-2 text-gray-700 hover:text-sky-700 {{ request()->routeIs('penyewa.riwayat-pembayaran*') ? 'text-sky-700 font-semibold' : '' }}">
                        Riwayat Pembayaran
                    </a>
                    
                    <!-- Profile & Logout in Mobile -->
                    <div class="border-t mt-3 pt-3">
                        <a href="{{ route('profile.edit') }}" 
                           class="block py-2 text-gray-700 hover:text-sky-700">
                            Profile
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" 
                                    class="block w-full text-left py-2 text-gray-700 hover:text-sky-700">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <main>
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="bg-white border-t mt-10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Logo & Description -->
                    <div>
                        <div class="flex items-center gap-2 mb-4">
                            <img src="{{ asset('img/rentalapp.png') }}" alt="Rental App Logo" class="w-10 h-10 object-contain">
                            <span class="text-xl font-bold text-gray-800">Rental App</span>
                        </div>
                        <p class="text-sm text-gray-600">
                            Platform rental properti terpercaya untuk kebutuhan hunian anda
                        </p>
                    </div>

                    <!-- Quick Links -->
                    <div>
                        <h3 class="text-sm font-semibold text-gray-800 mb-3">Tautan Cepat</h3>
                        <ul class="space-y-2 text-sm">
                            <li><a href="{{ route('penyewa.browse') }}" class="text-gray-600 hover:text-sky-700">Browse Properti</a></li>
                            <li><a href="{{ route('penyewa.pemesanan.index') }}" class="text-gray-600 hover:text-sky-700">Pemesanan Saya</a></li>
                            <li><a href="#" class="text-gray-600 hover:text-sky-700">Kontrak Saya</a></li>
                            <li><a href="#" class="text-gray-600 hover:text-sky-700">Riwayat Pembayaran</a></li>
                        </ul>
                    </div>

                    <!-- Contact -->
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

    <!-- Alpine.js for dropdowns -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>
