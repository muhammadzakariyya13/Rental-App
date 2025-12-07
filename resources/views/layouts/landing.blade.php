<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Rental App'))</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <!-- Guest Navbar -->
    <header class="bg-white px-6 md:px-12 py-4 flex justify-between items-center shadow">
        <div class="flex items-center gap-2 text-sky-700 font-bold text-lg">
            <a href="/" class="flex items-center gap-2">
                <img src="{{ asset('img/rentalapp.png') }}" alt="Rental App Logo" class="w-10 h-10 object-contain">
                <span class="text-xl font-bold text-gray-800">Rental App</span>
            </a>
        </div>

        <nav class="flex items-center gap-3 md:gap-4">
            <a href="{{ route('login') }}"
               class="text-gray-700 hover:text-sky-700 text-sm md:text-base transition">
               Login
            </a>
            <a href="{{ route('register') }}"
               class="bg-sky-700 hover:bg-sky-800 text-white text-sm md:text-base px-5 py-2 rounded-md transition">
               Daftar
            </a>
        </nav>
    </header>

    <!-- Page Content -->
    <main>
        @yield('content')
    </main>
</body>
</html>
