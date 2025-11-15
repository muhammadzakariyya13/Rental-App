@extends('layouts.penyewa')

@section('content')
    <!-- Page Header -->
    <div class="py-8 px-4 bg-gradient-to-r from-blue-600 to-blue-700 dark:from-blue-800 dark:to-blue-900 text-white">
        <div class="max-w-7xl mx-auto">
            <h1 class="text-3xl font-bold">🏠 Jelajahi Properti</h1>
            <p class="text-blue-100 mt-2">Temukan properti impian anda dari ribuan pilihan</p>
        </div>
    </div>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @include('penyewa.browse.components.search-filter')

            <!-- Properties Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-8">
                @forelse($properti as $item)
                    @include('penyewa.browse.components.property-card', [
                        'properti' => $item,
                        'id' => $item->id_properti,
                        'nama' => $item->nama_properti,
                        'lokasi' => $item->alamat,
                        'harga' => $item->harga,
                        'rating' => $item->rating ?? 0,
                        'review_count' => $item->reviews_count ?? 0,
                        'kamar_tidur' => $item->kamar_tidur ?? 0,
                        'kamar_mandi' => $item->kamar_mandi ?? 0,
                        'luas_tanah' => $item->luas_tanah ?? 0,
                        'deskripsi' => $item->deskripsi,
                        'icon' => match($item->tipe) {
                            'rumah' => '🏠',
                            'apartemen' => '🏢',
                            'vila' => '🏡',
                            'kontrakan' => '🏘️',
                            default => '🏠'
                        }
                    ])
                @empty
                    <div class="col-span-full text-center py-12">
                        <p class="text-gray-600 dark:text-gray-400 text-lg">Properti tidak ditemukan</p>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($properti->hasPages())
                <div class="mt-8">
                    {{ $properti->links() }}
                </div>
            @endif

            <!-- Empty State -->
            @if($properti->isEmpty())
                <div class="text-center py-12">
                    <p class="text-gray-600 dark:text-gray-400">Tampilkan lebih banyak properti dengan melakukan pencarian atau filter</p>
                </div>
            @endif
        </div>
    </div>
@endsection
