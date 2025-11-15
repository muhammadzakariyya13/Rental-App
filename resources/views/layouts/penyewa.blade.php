<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800">
            <!-- Navbar Penyewa -->
            <nav class="bg-white dark:bg-gray-800 shadow-lg border-b-4 border-blue-600 dark:border-blue-500 sticky top-0 z-40">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between items-center h-20">
                        <!-- Logo & Brand -->
                        <div class="flex items-center gap-8">
                            <a href="{{ route('penyewa.browse') }}" class="flex items-center gap-3 hover:opacity-80 transition">
                                <x-application-logo class="h-10 w-auto" />
                            </a>

                            <!-- Nav Links -->
                            <div class="hidden md:flex items-center gap-1">
                                <a href="{{ route('penyewa.browse') }}" class="px-3 py-2 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-700 font-medium transition {{ request()->routeIs('penyewa.browse') ? 'bg-blue-100 dark:bg-gray-700 text-blue-600 dark:text-blue-400' : '' }}">
                                    <span class="inline-block mr-2">🏠</span> Beranda
                                </a>
                                <a href="{{ route('penyewa.browse') }}" class="px-3 py-2 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-700 font-medium transition {{ request()->routeIs('penyewa.browse') ? 'bg-blue-100 dark:bg-gray-700 text-blue-600 dark:text-blue-400' : '' }}">
                                    <span class="inline-block mr-2">🔍</span> Jelajahi
                                </a>
                                <a href="{{ route('penyewa.pemesanan') }}" class="px-3 py-2 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-700 font-medium transition {{ request()->routeIs('penyewa.pemesanan') ? 'bg-blue-100 dark:bg-gray-700 text-blue-600 dark:text-blue-400' : '' }}">
                                    <span class="inline-block mr-2">📋</span> Pemesanan
                                </a>
                                <a href="{{ route('penyewa.reviews') }}" class="px-3 py-2 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-700 font-medium transition {{ request()->routeIs('penyewa.reviews') ? 'bg-blue-100 dark:bg-gray-700 text-blue-600 dark:text-blue-400' : '' }}">
                                    <span class="inline-block mr-2">⭐</span> Reviews
                                </a>
                            </div>
                        </div>

                        <!-- Right Side: User & Actions -->
                        <div class="flex items-center gap-4">
                            <!-- Search Bar (hidden on mobile) -->
                            <div class="hidden lg:flex items-center bg-gray-100 dark:bg-gray-700 rounded-full px-4 py-2">
                                <input type="text" placeholder="Cari properti..." class="bg-transparent text-gray-700 dark:text-gray-300 placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none w-40" />
                                <svg class="w-4 h-4 text-gray-600 dark:text-gray-400 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>

                            <!-- Notifications (optional) -->
                            <button class="relative p-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition">
                                <span class="text-xl">🔔</span>
                                <span class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1 -translate-y-1 bg-red-600 rounded-full">2</span>
                            </button>

                            <!-- User Menu Dropdown -->
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open" class="flex items-center gap-2 px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-medium transition">
                                    <span>👤</span>
                                    <span class="hidden sm:inline">{{ Auth::user()->username }}</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                                    </svg>
                                </button>

                                <!-- Dropdown Menu -->
                                <div x-show="open" @click.outside="open = false" class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-xl z-50">
                                    <a href="{{ route('profile.edit') }}" class="block px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-700 border-b border-gray-200 dark:border-gray-700">
                                        <span class="block font-medium">{{ Auth::user()->username }}</span>
                                        <span class="text-xs text-gray-500 dark:text-gray-400">Profil Anda</span>
                                    </a>
                                    <a href="{{ route('profile.edit') }}" class="block px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-700">
                                        ⚙️ Pengaturan
                                    </a>
                                    <a href="#" class="block px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-700">
                                        💳 Pembayaran
                                    </a>
                                    <form method="POST" action="{{ route('logout') }}" class="border-t border-gray-200 dark:border-gray-700">
                                        @csrf
                                        <button type="submit" class="w-full text-left px-4 py-3 text-red-600 hover:bg-red-50 dark:hover:bg-gray-700 font-medium">
                                            🚪 Keluar
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <!-- Mobile Menu Button -->
                            <button class="md:hidden p-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition" id="mobile-menu-btn">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Mobile Menu -->
                    <div id="mobile-menu" class="hidden md:hidden pb-4 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('penyewa.browse') }}" class="block px-3 py-2 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-700">
                            🏠 Beranda
                        </a>
                        <a href="{{ route('penyewa.browse') }}" class="block px-3 py-2 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-700">
                            🔍 Jelajahi
                        </a>
                        <a href="{{ route('penyewa.pemesanan') }}" class="block px-3 py-2 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-700">
                            📋 Pemesanan
                        </a>
                        <a href="{{ route('penyewa.reviews') }}" class="block px-3 py-2 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-700">
                            ⭐ Reviews
                        </a>
                    </div>
                </div>
            </nav>

            <!-- Page Heading -->
            <!-- Header will be defined in child views -->


            <!-- Page Content -->
            <main>
                @yield('content')
            </main>

            <!-- Footer -->
            <footer class="bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 mt-12">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
                        <div>
                            <h3 class="font-bold text-gray-900 dark:text-white mb-4">Rental App</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Platform terpercaya untuk menyewa properti impian Anda di seluruh Indonesia.</p>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900 dark:text-white mb-3">Navigasi</h4>
                            <ul class="space-y-2 text-sm text-gray-600 dark:text-gray-400">
                                <li><a href="#" class="hover:text-blue-600">Beranda</a></li>
                                <li><a href="#" class="hover:text-blue-600">Tentang Kami</a></li>
                                <li><a href="#" class="hover:text-blue-600">Properti</a></li>
                            </ul>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900 dark:text-white mb-3">Dukungan</h4>
                            <ul class="space-y-2 text-sm text-gray-600 dark:text-gray-400">
                                <li><a href="#" class="hover:text-blue-600">Hubungi Kami</a></li>
                                <li><a href="#" class="hover:text-blue-600">FAQ</a></li>
                                <li><a href="#" class="hover:text-blue-600">Kebijakan Privasi</a></li>
                            </ul>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900 dark:text-white mb-3">Ikuti Kami</h4>
                            <div class="flex gap-4">
                                <a href="#" class="text-gray-600 dark:text-gray-400 hover:text-blue-600">📘 Facebook</a>
                                <a href="#" class="text-gray-600 dark:text-gray-400 hover:text-blue-600">🐦 Twitter</a>
                                <a href="#" class="text-gray-600 dark:text-gray-400 hover:text-blue-600">📷 Instagram</a>
                            </div>
                        </div>
                    </div>
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-8 text-center text-sm text-gray-600 dark:text-gray-400">
                        <p>&copy; 2025 Rental App. Semua hak dilindungi.</p>
                    </div>
                </div>
            </footer>
        </div>

        <script>
            // Mobile menu toggle
            document.getElementById('mobile-menu-btn')?.addEventListener('click', function() {
                document.getElementById('mobile-menu').classList.toggle('hidden');
            });
        </script>
    </body>
</html>
