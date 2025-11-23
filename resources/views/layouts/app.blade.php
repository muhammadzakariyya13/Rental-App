<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Rental App') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        <nav class="bg-white shadow-lg border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <!-- Logo -->
                    <div class="flex items-center">
                        <a href="{{ auth()->check() ? route('dashboard') : '/' }}" class="flex items-center">
                            <span class="text-2xl font-bold text-blue-600">🏠 Rental App</span>
                        </a>
                    </div>

                    <!-- Navigation Links -->
                    <div class="hidden md:flex items-center space-x-8">
                        @auth
                            <!-- User Navigation -->
                            @if(auth()->user()->role && auth()->user()->role->nama === 'user')
                                <a href="{{ route('properti.index') }}" 
                                   class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('properti.*') ? 'text-blue-600 bg-blue-50' : '' }}">
                                    📋 Browse Properti
                                </a>
                                <a href="{{ route('user.pemesanan') }}" 
                                   class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('user.pemesanan') ? 'text-blue-600 bg-blue-50' : '' }}">
                                    📝 Pemesanan Saya
                                </a>
                                <a href="{{ route('user.kontrak') }}" 
                                   class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('user.kontrak') ? 'text-blue-600 bg-blue-50' : '' }}">
                                    📄 Kontrak Saya
                                </a>
                                <a href="{{ route('payment.history') }}" 
                                   class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('payment.history') ? 'text-blue-600 bg-blue-50' : '' }}">
                                    💳 Riwayat Pembayaran
                                </a>
                            @endif

                            <!-- Admin Navigation -->
                            @if(auth()->user()->role && auth()->user()->role->nama === 'admin')
                                <a href="{{ route('admin.dashboard') }}" 
                                   class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('admin.dashboard') ? 'text-blue-600 bg-blue-50' : '' }}">
                                    🏠 Admin Dashboard
                                </a>
                                <div class="relative group">
                                    <button class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium flex items-center">
                                        🛠️ Kelola <svg class="ml-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                                    </button>
                                    <div class="absolute left-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200">
                                        <div class="py-1">
                                            <a href="{{ route('admin.properti.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Properti</a>
                                            <a href="{{ route('admin.pemesanan.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Pemesanan</a>
                                            <a href="{{ route('admin.kontrak.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Kontrak</a>
                                            <a href="{{ route('admin.akun.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Akun</a>
                                            <a href="{{ route('admin.payments.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Pembayaran</a>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @else
                            <!-- Guest Navigation -->
                            <a href="{{ route('properti.index') }}" 
                               class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium">
                                📋 Lihat Properti
                            </a>
                        @endauth
                    </div>

                    <!-- User Info & Actions -->
                    <div class="flex items-center space-x-4">
                        @auth
                            <!-- User Dropdown -->
                            <div class="relative group">
                                <button class="flex items-center text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-2">
                                            <span class="text-blue-600 font-medium text-xs">
                                                {{ substr(Auth::user()->nama ?? Auth::user()->username, 0, 1) }}
                                            </span>
                                        </div>
                                        <div class="hidden md:block">
                                            <div class="text-sm font-medium">{{ Auth::user()->nama ?? Auth::user()->username }}</div>
                                            <div class="text-xs text-gray-500">{{ Auth::user()->role->nama ?? 'User' }}</div>
                                        </div>
                                        <svg class="ml-2 h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                                    </div>
                                </button>
                                <div class="absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200">
                                    <div class="py-1">
                                        <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">🏠 Dashboard</a>
                                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">👤 Profile</a>
                                        <hr class="my-1">
                                        <form method="POST" action="{{ route('logout') }}" class="block">
                                            @csrf
                                            <button type="submit" class="w-full text-left block px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                                🚪 Logout
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @else
                            <a href="{{ route('login') }}" class="text-gray-600 hover:text-gray-900 px-3 py-2 text-sm font-medium">Login</a>
                            <a href="{{ route('register') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md text-sm font-medium transition duration-200">
                                Daftar
                            </a>
                        @endauth
                    </div>

                    <!-- Mobile menu button -->
                    <div class="md:hidden flex items-center">
                        <button class="text-gray-700 hover:text-blue-600 focus:outline-none focus:text-blue-600" onclick="toggleMobileMenu()">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Mobile Navigation -->
                <div id="mobile-menu" class="md:hidden hidden">
                    <div class="px-2 pt-2 pb-3 space-y-1 border-t border-gray-200">
                        @auth
                            @if(auth()->user()->role && auth()->user()->role->nama === 'user')
                                <a href="{{ route('properti.index') }}" class="block px-3 py-2 text-gray-700 hover:text-blue-600">📋 Browse Properti</a>
                                <a href="{{ route('user.pemesanan') }}" class="block px-3 py-2 text-gray-700 hover:text-blue-600">📝 Pemesanan Saya</a>
                                <a href="{{ route('user.kontrak') }}" class="block px-3 py-2 text-gray-700 hover:text-blue-600">📄 Kontrak Saya</a>
                                <a href="{{ route('payment.history') }}" class="block px-3 py-2 text-gray-700 hover:text-blue-600">💳 Riwayat Pembayaran</a>
                            @endif
                            @if(auth()->user()->role && auth()->user()->role->nama === 'admin')
                                <a href="{{ route('admin.dashboard') }}" class="block px-3 py-2 text-gray-700 hover:text-blue-600">🏠 Admin Dashboard</a>
                                <a href="{{ route('admin.properti.index') }}" class="block px-3 py-2 text-gray-700 hover:text-blue-600">Kelola Properti</a>
                                <a href="{{ route('admin.payments.index') }}" class="block px-3 py-2 text-gray-700 hover:text-blue-600">Kelola Pembayaran</a>
                            @endif
                        @else
                            <a href="{{ route('properti.index') }}" class="block px-3 py-2 text-gray-700 hover:text-blue-600">📋 Lihat Properti</a>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <script>
            function toggleMobileMenu() {
                const menu = document.getElementById('mobile-menu');
                menu.classList.toggle('hidden');
            }
        </script>

        <main class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                {{ $slot ?? '' }}
                @yield('content')
            </div>
        </main>
    </div>
</body>
</html>