@extends('layouts.penyewa')

@section('content')
    <!-- Page Header -->
    <div class="py-8 px-4 bg-gradient-to-r from-blue-600 to-blue-700 dark:from-blue-800 dark:to-blue-900 text-white">
        <div class="max-w-7xl mx-auto">
            <h1 class="text-3xl font-bold">📋 Pemesanan Saya</h1>
            <p class="text-blue-100 mt-2">Kelola semua pemesanan properti anda</p>
        </div>
    </div>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Filter Tabs -->
            <div class="flex gap-4 mb-8 border-b border-gray-200 dark:border-gray-700">
                <button class="px-4 py-2 font-medium text-blue-600 border-b-2 border-blue-600 hover:text-blue-700">
                    📋 Semua
                </button>
                <button class="px-4 py-2 font-medium text-gray-600 dark:text-gray-400 border-b-2 border-transparent hover:text-gray-800 dark:hover:text-gray-200">
                    ⏳ Menunggu
                </button>
                <button class="px-4 py-2 font-medium text-gray-600 dark:text-gray-400 border-b-2 border-transparent hover:text-gray-800 dark:hover:text-gray-200">
                    ✅ Diterima
                </button>
                <button class="px-4 py-2 font-medium text-gray-600 dark:text-gray-400 border-b-2 border-transparent hover:text-gray-800 dark:hover:text-gray-200">
                    ❌ Ditolak
                </button>
            </div>

            <!-- Booking Cards -->
            <div class="space-y-6">
                @forelse($pemesanan as $item)
                    @include('penyewa.pemesanan.components.booking-card', [
                        'id' => $item->id_pemesanan,
                        'nama_properti' => $item->properti->nama_properti,
                        'lokasi' => $item->properti->alamat,
                        'check_in' => $item->check_in->format('d F Y'),
                        'check_out' => $item->check_out->format('d F Y'),
                        'durasi' => $item->check_out->diffInDays($item->check_in),
                        'total_harga' => $item->total_harga,
                        'status' => $item->status
                    ])
                @empty
                    <div class="text-center py-12 bg-white dark:bg-gray-800 rounded-lg">
                        <p class="text-gray-600 dark:text-gray-400 text-lg">Anda belum memiliki pemesanan</p>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($pemesanan->hasPages())
                <div class="mt-8">
                    {{ $pemesanan->links() }}
                </div>
            @endif

            <!-- Empty State -->
            <div class="text-center py-12">
                <p class="text-gray-600 dark:text-gray-400">Anda belum memiliki pemesanan</p>
                <a href="{{ route('penyewa.browse') }}" class="mt-4 inline-block px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200">
                    🔍 Jelajahi Properti
                </a>
                
                <!-- Link untuk testing -->
                <div class="mt-6">
                    <a href="{{ route('penyewa.reviews') }}" class="text-blue-600 dark:text-blue-400 hover:underline">
                        Test: Ke halaman Reviews
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
