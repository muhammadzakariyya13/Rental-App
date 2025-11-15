@extends('layouts.penyewa')

@section('content')
    <!-- Page Header -->
    <div class="py-8 px-4 bg-gradient-to-r from-blue-600 to-blue-700 dark:from-blue-800 dark:to-blue-900 text-white">
        <div class="max-w-7xl mx-auto">
            <h1 class="text-3xl font-bold">{{ $properti->nama_properti }}</h1>
            <p class="text-blue-100 mt-2">Detail properti lengkap dan informasi pemesanan</p>
        </div>
    </div>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Back Button -->
            <a href="{{ route('penyewa.browse') }}" class="inline-flex items-center gap-2 text-blue-600 dark:text-blue-400 hover:underline mb-6">
                ← Kembali ke Daftar
            </a>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Content -->
                <div class="lg:col-span-2">
                    <!-- Image Gallery -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden mb-6">
                        @if($properti->gambar)
                            <!-- Base64 Image dari column gambar -->
                            <img src="data:image/jpeg;base64,{{ $properti->gambar }}" alt="{{ $properti->nama_properti }}" class="w-full h-96 object-cover">
                        @elseif($properti->images && $properti->images->isNotEmpty())
                            @php
                                $primaryImage = $properti->images->where('is_primary', true)->first() ?? $properti->images->first();
                            @endphp
                            @if(strpos($primaryImage->image_url, 'data:') === 0)
                                <!-- Base64 Image dari properti_images -->
                                <img src="{{ $primaryImage->image_url }}" alt="{{ $properti->nama_properti }}" class="w-full h-96 object-cover">
                            @else
                                <!-- Regular Image URL -->
                                <img src="{{ asset($primaryImage->image_url) }}" alt="{{ $properti->nama_properti }}" class="w-full h-96 object-cover">
                            @endif
                        @else
                            <!-- Fallback Gradient -->
                            <div class="bg-gradient-to-br from-blue-400 to-blue-600 h-96 flex items-center justify-center text-white text-6xl">
                                {{ match($properti->tipe ?? 'rumah') {
                                    'rumah' => '🏠',
                                    'apartemen' => '🏢',
                                    'vila' => '🏡',
                                    'kontrakan' => '🏘️',
                                    default => '🏠'
                                } }}
                            </div>
                        @endif
                    </div>

                    <!-- Property Details -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-6">
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">
                            {{ $properti->nama_properti }}
                        </h3>

                        <p class="text-gray-600 dark:text-gray-400 text-lg mb-4">
                            📍 {{ $properti->alamat }}
                        </p>

                        <!-- Rating -->
                        <div class="flex items-center gap-4 mb-6 pb-6 border-b border-gray-200 dark:border-gray-700">
                            <div class="flex text-yellow-400 text-2xl">
                                @for ($i = 0; $i < 5; $i++)
                                    @if($i < ($properti->rating ?? 0))
                                        ⭐
                                    @else
                                        ☆
                                    @endif
                                @endfor
                            </div>
                            <span class="text-gray-600 dark:text-gray-400">
                                {{ $properti->rating ?? 0 }}/5 ({{ count($reviews) }} reviews)
                            </span>
                        </div>

                        <!-- Property Info -->
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <div>
                                <p class="text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase">Kamar Tidur</p>
                                <p class="text-xl font-bold text-gray-800 dark:text-gray-100">🛏️ {{ $properti->kamar_tidur ?? 0 }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase">Kamar Mandi</p>
                                <p class="text-xl font-bold text-gray-800 dark:text-gray-100">🚿 {{ $properti->kamar_mandi ?? 0 }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase">Luas Tanah</p>
                                <p class="text-xl font-bold text-gray-800 dark:text-gray-100">📐 {{ $properti->luas_tanah ?? 0 }}m²</p>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase">Tipe</p>
                                <p class="text-xl font-bold text-gray-800 dark:text-gray-100 capitalize">{{ $properti->tipe ?? 'N/A' }}</p>
                            </div>
                        </div>

                        <!-- Description -->
                        <div>
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Deskripsi</h4>
                            <p class="text-gray-700 dark:text-gray-300 leading-relaxed">
                                {{ $properti->deskripsi }}
                            </p>
                        </div>
                    </div>

                    <!-- Reviews Section -->
                    @if(count($reviews) > 0)
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">
                                ⭐ Ulasan ({{ count($reviews) }})
                            </h4>

                            <div class="space-y-4">
                                @foreach($reviews as $review)
                                    <div class="border-b border-gray-200 dark:border-gray-700 pb-4 last:border-0">
                                        <div class="flex justify-between items-start mb-2">
                                            <div>
                                                <p class="font-medium text-gray-900 dark:text-white">
                                                    {{ $review->akun->username ?? 'Anonymous' }}
                                                </p>
                                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                                    {{ $review->created_at->format('d F Y') }}
                                                </p>
                                            </div>
                                            <div class="flex text-yellow-400">
                                                @for ($i = 0; $i < $review->rating; $i++)
                                                    ⭐
                                                @endfor
                                            </div>
                                        </div>
                                        <p class="text-gray-700 dark:text-gray-300">
                                            {{ $review->isi_review }}
                                        </p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-1">
                    <!-- Price Card -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 sticky top-24">
                        <div class="mb-6">
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Harga Per Hari</p>
                            <p class="text-3xl font-bold text-blue-600 dark:text-blue-400">
                                Rp {{ number_format($properti->harga, 0, ',', '.') }}
                            </p>
                        </div>

                        <button class="w-full px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg transition-colors duration-200 mb-4">
                            📅 Pesan Sekarang
                        </button>

                        <button class="w-full px-6 py-3 border-2 border-blue-600 text-blue-600 dark:text-blue-400 dark:border-blue-400 font-bold rounded-lg hover:bg-blue-50 dark:hover:bg-blue-900 transition-colors duration-200">
                            ❤️ Simpan
                        </button>

                        <!-- Contact Info -->
                        <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                            <h5 class="font-semibold text-gray-900 dark:text-white mb-4">Hubungi Pemilik</h5>
                            <a href="#" class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium transition-colors">
                                💬 WhatsApp
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
