<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Detail Properti
            </h2>
            <div class="flex space-x-3">
                <a href="{{ route('pemilik.properti.edit', $properti->id_properti) }}" 
                   class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Edit
                </a>
                <a href="{{ route('pemilik.properti') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Success Message -->
            @if(session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <!-- Header Info -->
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex justify-between items-start">
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $properti->nama }}</h1>
                            <p class="text-gray-600 text-lg mb-4">{{ $properti->alamat }}</p>
                            <div class="flex items-center space-x-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ 
                                    $properti->status == 'tersedia' ? 'bg-green-100 text-green-800' : 
                                    ($properti->status == 'disewa' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') 
                                }}">
                                    {{ ucfirst($properti->status) }}
                                </span>
                                <span class="text-gray-500 text-sm">#{{ $properti->id_properti }}</span>
                                <span class="text-gray-500 text-sm">Ditambahkan {{ $properti->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-3xl font-bold text-green-600">Rp {{ number_format($properti->harga, 0, ',', '.') }}</div>
                            <div class="text-gray-500">per bulan</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <!-- Total Pendapatan -->
                <div class="bg-gradient-to-r from-green-500 to-green-600 overflow-hidden shadow-lg sm:rounded-lg">
                    <div class="p-6 text-white">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <div class="text-lg leading-6 font-medium text-white">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</div>
                                <div class="text-green-100">Total Pendapatan</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Jumlah Penyewa -->
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 overflow-hidden shadow-lg sm:rounded-lg">
                    <div class="p-6 text-white">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <div class="text-lg leading-6 font-medium text-white">{{ $jumlahPenyewa }}</div>
                                <div class="text-blue-100">Total Penyewa</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Rating -->
                <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 overflow-hidden shadow-lg sm:rounded-lg">
                    <div class="p-6 text-white">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <div class="text-lg leading-6 font-medium text-white">
                                    {{ number_format($ratingRataRata, 1) }}/5
                                    @if($totalReviews > 0)
                                        <span class="text-sm">({{ $totalReviews }})</span>
                                    @endif
                                </div>
                                <div class="text-yellow-100">Rating Rata-rata</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Hari Disewa -->
                <div class="bg-gradient-to-r from-purple-500 to-purple-600 overflow-hidden shadow-lg sm:rounded-lg">
                    <div class="p-6 text-white">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <div class="text-lg leading-6 font-medium text-white">0</div>
                                <div class="text-purple-100">Hari Disewa</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Detail Properti -->
                <div class="lg:col-span-2">
                    <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900">Detail Properti</h3>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Tipe Properti</label>
                                    <div class="flex items-center text-sm text-gray-900">
                                        <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                        </svg>
                                        {{ ucfirst($properti->tipe) }}
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Harga Sewa</label>
                                    <div class="flex items-center text-sm text-gray-900">
                                        <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                        </svg>
                                        Rp {{ number_format($properti->harga, 0, ',', '.') }} / bulan
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Kamar Tidur</label>
                                    <div class="flex items-center text-sm text-gray-900">
                                        <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2 2z"></path>
                                        </svg>
                                        {{ $properti->kamar_tidur }} Kamar
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Kamar Mandi</label>
                                    <div class="flex items-center text-sm text-gray-900">
                                        <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"></path>
                                        </svg>
                                        {{ $properti->kamar_mandi }} Kamar
                                    </div>
                                </div>

                                @if($properti->luas_tanah)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Luas Tanah</label>
                                    <div class="flex items-center text-sm text-gray-900">
                                        <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path>
                                        </svg>
                                        {{ $properti->luas_tanah }} m²
                                    </div>
                                </div>
                                @endif

                                @if($properti->luas_bangunan)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Luas Bangunan</label>
                                    <div class="flex items-center text-sm text-gray-900">
                                        <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"></path>
                                        </svg>
                                        {{ $properti->luas_bangunan }} m²
                                    </div>
                                </div>
                                @endif
                            </div>

                            @if($properti->deskripsi)
                            <div class="mt-6">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                                <div class="text-gray-900 text-sm leading-relaxed">
                                    {{ $properti->deskripsi }}
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Foto Properti -->
                    <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg mt-6">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900">Foto Properti</h3>
                    <!-- FOTO PROPERTI SECTION -->
                    <div class="bg-white overflow-hidden shadow-lg rounded-lg mt-6">
                        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                            <div class="flex justify-between items-center">
                                <h3 class="text-lg font-medium text-gray-900">Foto Properti</h3>
                                <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded">
                                    {{ $properti->images->count() }} Foto
                                </span>
                            </div>
                        </div>
                        
                        <div class="p-6">
                            @if($properti->images->count() > 0)
                                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                                    @foreach($properti->images as $image)
                                        <div class="relative group">
                                            <img src="{{ $image->image_url }}" 
                                                alt="Foto {{ $properti->nama }}"
                                                class="w-full h-32 object-cover rounded-lg shadow-sm">
                                            
                                            <!-- Primary Badge -->
                                            @if($image->is_primary)
                                                <div class="absolute top-2 left-2 bg-green-500 text-white text-xs px-2 py-1 rounded-full">
                                                    Utama
                                                </div>
                                            @endif
                                            
                                            <!-- Action Buttons -->
                                            <div class="absolute top-2 right-2 flex space-x-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                                @if(!$image->is_primary)
                                                    <button onclick="setPrimaryImage({{ $properti->id_properti }}, {{ $image->id }})"
                                                            class="bg-blue-500 hover:bg-blue-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs">
                                                        ★
                                                    </button>
                                                @endif
                                                
                                                <button onclick="deleteImage({{ $properti->id_properti }}, {{ $image->id }})"
                                                        class="bg-red-500 hover:bg-red-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs">
                                                    ×
                                                </button>
                                            </div>
                                            
                                            <!-- Order Number -->
                                            <div class="absolute bottom-2 left-2 bg-black bg-opacity-50 text-white text-xs px-2 py-1 rounded">
                                                {{ $image->order_index + 1 }}
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-8">
                                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 002 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <p class="text-gray-500 mb-4">Belum ada foto untuk properti ini</p>
                                    <a href="{{ route('pemilik.properti.edit', $properti->id_properti) }}" 
                                    class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
                                        Upload Foto
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Reviews & Ratings -->
                    <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg mt-6">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <div class="flex justify-between items-center">
                                <h3 class="text-lg font-medium text-gray-900">Reviews & Rating</h3>
                                <span class="text-sm text-gray-500">{{ $totalReviews }} review{{ $totalReviews != 1 ? 's' : '' }}</span>
                            </div>
                        </div>
                        
                        @if($totalReviews > 0)
                        <div class="p-6">
                            <!-- Rating Summary -->
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                                <!-- Overall Rating -->
                                <div class="text-center lg:text-left">
                                    <div class="flex items-center justify-center lg:justify-start mb-2">
                                        <span class="text-5xl font-bold text-gray-900">{{ number_format($ratingRataRata, 1) }}</span>
                                        <div class="ml-4">
                                            <div class="flex items-center mb-1">
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($i <= floor($ratingRataRata))
                                                        <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                        </svg>
                                                    @elseif($i == ceil($ratingRataRata) && $ratingRataRata - floor($ratingRataRata) >= 0.5)
                                                        <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                        </svg>
                                                    @else
                                                        <svg class="w-5 h-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                        </svg>
                                                    @endif
                                                @endfor
                                            </div>
                                            <p class="text-sm text-gray-500">Berdasarkan {{ $totalReviews }} review</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Rating Breakdown -->
                                <div>
                                    @foreach($ratingDistribution as $rating => $count)
                                    <div class="flex items-center mb-2">
                                        <span class="text-sm font-medium text-gray-700 w-8">{{ $rating }}★</span>
                                        <div class="flex-1 mx-3">
                                            <div class="bg-gray-200 rounded-full h-2">
                                                <div class="bg-yellow-400 h-2 rounded-full" style="width: {{ $properti->ratingPercentage($rating) }}%"></div>
                                            </div>
                                        </div>
                                        <span class="text-sm text-gray-500 w-12 text-right">{{ $count }}</span>
                                    </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Recent Reviews -->
                            <div>
                                <h4 class="text-lg font-medium text-gray-900 mb-4">Review Terbaru</h4>
                                
                                @if($recentReviews->count() > 0)
                                <div class="space-y-6">
                                    @foreach($recentReviews as $review)
                                    <div class="border-b border-gray-200 pb-6 last:border-b-0 last:pb-0">
                                        <div class="flex items-start space-x-4">
                                            <!-- Avatar -->
                                            <div class="flex-shrink-0">
                                                <div class="w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center">
                                                    <span class="text-sm font-medium text-gray-700">
                                                        {{ strtoupper(substr($review->penyewa->name ?? 'U', 0, 1)) }}
                                                    </span>
                                                </div>
                                            </div>
                                            
                                            <!-- Review Content -->
                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-center justify-between mb-1">
                                                    <h5 class="text-sm font-medium text-gray-900">
                                                        {{ $review->penyewa->name ?? 'User' }}
                                                    </h5>
                                                    <div class="flex items-center space-x-2">
                                                        <span class="text-xs text-gray-500">
                                                            {{ $review->tanggal_review->diffForHumans() }}
                                                        </span>
                                                        <!-- Action Buttons -->
                                                        <div class="flex space-x-1">
                                                            <button onclick="showReplyModal({{ $review->id_review }})" 
                                                                    class="text-blue-600 hover:text-blue-700 text-xs font-medium">
                                                                Balas
                                                            </button>
                                                            <form action="{{ route('pemilik.review.destroy', $review->id_review) }}" 
                                                                  method="POST" 
                                                                  class="inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" 
                                                                        onclick="return confirm('Yakin hapus review ini?')"
                                                                        class="text-red-600 hover:text-red-700 text-xs font-medium">
                                                                    Hapus
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <!-- Stars -->
                                                <div class="flex items-center mb-2">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        @if($i <= $review->rating)
                                                            <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                            </svg>
                                                        @else
                                                            <svg class="w-4 h-4 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                            </svg>
                                                        @endif
                                                    @endfor
                                                    <span class="ml-2 text-sm text-gray-600">{{ $review->rating }}/5</span>
                                                </div>
                                                
                                                <!-- Review Text -->
                                                @if($review->review)
                                                <p class="text-sm text-gray-700 leading-relaxed mb-3">
                                                    {{ $review->review }}
                                                </p>
                                                @endif

                                                <!-- Owner Reply -->
                                                @if($review->pemilik_reply)
                                                <div class="bg-gray-50 p-3 rounded-lg mt-3">
                                                    <div class="flex items-center mb-1">
                                                        <span class="text-sm font-medium text-gray-900">Balasan Pemilik:</span>
                                                        <span class="text-xs text-gray-500 ml-2">{{ $review->reply_date->diffForHumans() }}</span>
                                                    </div>
                                                    <p class="text-sm text-gray-700">{{ $review->pemilik_reply }}</p>
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>

                                @if($totalReviews > 5)
                                <div class="mt-6 text-center">
                                    <button class="text-blue-600 hover:text-blue-700 font-medium text-sm">
                                        Lihat Semua {{ $totalReviews }} Review →
                                    </button>
                                </div>
                                @endif

                                @else
                                <div class="text-center py-8 text-gray-500">
                                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                    </svg>
                                    <p>Belum ada review untuk properti ini</p>
                                </div>
                                @endif
                            </div>
                        </div>
                        
                        @else
                        <div class="p-6">
                            <div class="text-center py-8">
                                <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                </svg>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">Belum Ada Rating</h3>
                                <p class="text-gray-500">Properti ini belum mendapat rating dari penyewa.</p>
                                <p class="text-gray-500 text-sm mt-2">Rating akan muncul setelah ada penyewa yang memberikan review.</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Actions & Quick Info -->
                <div class="space-y-6">
                    <!-- Quick Actions -->
                    <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900">Quick Actions</h3>
                        </div>
                        <div class="p-6 space-y-3">
                            <a href="{{ route('pemilik.properti.edit', $properti->id_properti) }}" 
                               class="w-full bg-yellow-500 hover:bg-yellow-600 text-white font-medium py-2 px-4 rounded inline-flex items-center justify-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Edit Properti
                            </a>

                            @if($properti->status == 'tersedia')
                            <button class="w-full bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded inline-flex items-center justify-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                </svg>
                                Promosikan
                            </button>
                            @endif

                            <button class="w-full bg-green-500 hover:bg-green-600 text-white font-medium py-2 px-4 rounded inline-flex items-center justify-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Lihat Laporan
                            </button>

                            <form action="{{ route('pemilik.properti.destroy', $properti->id_properti) }}" method="POST" class="w-full">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        onclick="return confirm('Yakin ingin hapus properti {{ $properti->nama }}?')"
                                        class="w-full bg-red-500 hover:bg-red-600 text-white font-medium py-2 px-4 rounded inline-flex items-center justify-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                    Hapus Properti
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Property Info -->
                    <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900">Informasi</h3>
                        </div>
                        <div class="p-6 space-y-4">
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-500">ID Properti</span>
                                <span class="text-sm font-medium text-gray-900">#{{ $properti->id_properti }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-500">Status</span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ 
                                    $properti->status == 'tersedia' ? 'bg-green-100 text-green-800' : 
                                    ($properti->status == 'disewa' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') 
                                }}">
                                    {{ ucfirst($properti->status) }}
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-500">Dibuat</span>
                                <span class="text-sm font-medium text-gray-900">{{ $properti->created_at->format('d M Y') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-500">Terakhir Update</span>
                                <span class="text-sm font-medium text-gray-900">{{ $properti->updated_at->format('d M Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Reply Modal -->
    <div id="replyModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Balas Review</h3>
                <form id="replyForm" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="reply" class="block text-sm font-medium text-gray-700 mb-2">Balasan Anda</label>
                        <textarea id="reply" 
                                  name="reply" 
                                  rows="3" 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                  placeholder="Tulis balasan untuk review ini..."
                                  required></textarea>
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button type="button" 
                                onclick="hideReplyModal()"
                                class="px-4 py-2 text-gray-500 bg-gray-200 rounded-md hover:bg-gray-300">
                            Batal
                        </button>
                        <button type="submit"
                                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                            Kirim Balasan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
    // Set primary image
    function setPrimaryImage(propertiId, imageId) {
        fetch(`/pemilik/properti/${propertiId}/images/${imageId}/primary`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        })
        .catch(error => console.error('Error:', error));
    }

    // Delete image
    function deleteImage(propertiId, imageId) {
        if (confirm('Apakah Anda yakin ingin menghapus foto ini?')) {
            fetch(`/pemilik/properti/${propertiId}/images/${imageId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            })
            .catch(error => console.error('Error:', error));
        }
    }

    function showReplyModal(reviewId) {
        document.getElementById('replyModal').classList.remove('hidden');
        document.getElementById('replyForm').action = `/pemilik/review/${reviewId}/reply`;
        document.getElementById('reply').focus();
    }

    function hideReplyModal() {
        document.getElementById('replyModal').classList.add('hidden');
        document.getElementById('reply').value = '';
    }

    // Close modal when clicking outside
    document.getElementById('replyModal').addEventListener('click', function(e) {
        if (e.target === this) {
            hideReplyModal();
        }
    });
    </script>
</x-app-layout>