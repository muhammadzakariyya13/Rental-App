<!-- Property Card -->
<div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden hover:shadow-lg transition transform hover:scale-105">
    <div class="relative">
        @if(isset($properti) && $properti->gambar)
            <!-- Base64 Image dari column gambar -->
            <img src="data:image/jpeg;base64,{{ $properti->gambar }}" alt="{{ $nama }}" class="w-full h-48 object-cover">
        @elseif(isset($properti) && $properti->images && $properti->images->isNotEmpty())
            @php
                $primaryImage = $properti->images->where('is_primary', true)->first() ?? $properti->images->first();
            @endphp
            @if(strpos($primaryImage->image_url, 'data:') === 0)
                <!-- Base64 Image dari properti_images -->
                <img src="{{ $primaryImage->image_url }}" alt="{{ $nama }}" class="w-full h-48 object-cover">
            @else
                <!-- Regular Image URL -->
                <img src="{{ asset($primaryImage->image_url) }}" alt="{{ $nama }}" class="w-full h-48 object-cover">
            @endif
        @else
            <!-- Fallback Gradient -->
            <div class="bg-gradient-to-r from-blue-400 to-blue-600 h-48 flex items-center justify-center text-white text-6xl">
                {{ $icon ?? '🏠' }}
            </div>
        @endif
        <span class="absolute top-4 right-4 bg-red-500 text-white px-3 py-1 rounded-full text-sm font-bold">
            Rp {{ number_format($harga, 0, ',', '.') }}/hari
        </span>
    </div>
    <div class="p-6">
        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">{{ $nama }}</h3>
        <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">📍 {{ $lokasi }}</p>
        
        <div class="flex items-center gap-2 mb-4">
            <div class="flex text-yellow-400">
                @for ($i = 0; $i < $rating; $i++)
                    <span>⭐</span>
                @endfor
            </div>
            <span class="text-sm text-gray-600 dark:text-gray-400">({{ $review_count }} ulasan)</span>
        </div>

        <div class="grid grid-cols-3 gap-2 mb-4 text-sm text-gray-600 dark:text-gray-400">
            <div class="flex items-center gap-1">
                <span>🛏️</span> {{ $kamar_tidur }} Kamar
            </div>
            <div class="flex items-center gap-1">
                <span>🛁</span> {{ $kamar_mandi }} Kamar Mandi
            </div>
            <div class="flex items-center gap-1">
                <span>📐</span> {{ $luas_tanah }} m²
            </div>
        </div>

        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4 line-clamp-2">{{ $deskripsi }}</p>
        
        <a href="{{ route('penyewa.browse.show', $id) }}" class="block w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition text-center">
            Lihat Detail & Pesan
        </a>
    </div>
</div>
