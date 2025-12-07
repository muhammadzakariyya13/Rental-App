@extends('layouts.penyewa')

@section('title', 'Beri Review')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- Header --}}
        <div class="mb-8">
            <a href="{{ route('penyewa.pemesanan.index') }}" class="inline-flex items-center text-sky-600 hover:text-sky-700 mb-4">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali ke Pemesanan
            </a>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Beri Review</h1>
            <p class="text-gray-600">Bagikan pengalaman Anda tentang properti ini</p>
        </div>

        {{-- Property Info Card --}}
        <div class="bg-white rounded-xl shadow-md overflow-hidden mb-8">
            <div class="md:flex">
                {{-- Image --}}
                <div class="md:w-64 h-48 bg-gray-200">
                    @if($pemesanan->properti->gambar)
                        <img src="{{ asset($pemesanan->properti->gambar) }}" 
                             alt="{{ $pemesanan->properti->nama }}"
                             class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-sky-50 to-sky-100">
                            <span class="text-6xl">🏠</span>
                        </div>
                    @endif
                </div>

                {{-- Info --}}
                <div class="p-6 flex-1">
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ $pemesanan->properti->nama }}</h3>
                    <p class="text-gray-600 flex items-center gap-2 mb-4">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        {{ $pemesanan->properti->alamat }}
                    </p>
                    <div class="grid grid-cols-3 gap-4 text-sm">
                        <div>
                            <p class="text-gray-600 mb-1">Order ID</p>
                            <p class="font-semibold text-gray-900">#{{ $pemesanan->id_pemesanan }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600 mb-1">Durasi Sewa</p>
                            <p class="font-semibold text-gray-900">{{ $pemesanan->lama_sewa }} Bulan</p>
                        </div>
                        <div>
                            <p class="text-gray-600 mb-1">Total Harga</p>
                            <p class="font-bold text-sky-600">Rp {{ number_format($pemesanan->total_harga, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Review Form --}}
        <div class="bg-white rounded-xl shadow-md p-8">
            <form action="{{ route('penyewa.reviews.store') }}" method="POST">
                @csrf
                <input type="hidden" name="pemesanan_id" value="{{ $pemesanan->id_pemesanan }}">
                <input type="hidden" name="properti_id" value="{{ $pemesanan->id_properti }}">

                {{-- Rating --}}
                <div class="mb-6">
                    <label class="block text-lg font-semibold text-gray-900 mb-3">Rating Bintang</label>
                    <p class="text-sm text-gray-600 mb-4">Berikan rating untuk properti ini (klik bintang)</p>
                    <div class="flex gap-2" id="starRating">
                        @for($i = 1; $i <= 5; $i++)
                        <button type="button" 
                                data-rating="{{ $i }}"
                                class="star-btn text-5xl transition-all hover:scale-110 focus:outline-none"
                                onclick="setRating({{ $i }})">
                            <span class="star-icon text-gray-300">★</span>
                        </button>
                        @endfor
                    </div>
                    <input type="hidden" name="rating" id="ratingInput" value="" required>
                    <p class="text-sm text-gray-600 mt-2">
                        <span id="ratingText">Pilih rating Anda</span>
                    </p>
                    @error('rating')
                        <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Review Text --}}
                <div class="mb-6">
                    <label for="review" class="block text-lg font-semibold text-gray-900 mb-3">
                        Tulis Review Anda
                    </label>
                    <p class="text-sm text-gray-600 mb-4">
                        Ceritakan pengalaman Anda secara detail (minimal 10 karakter)
                    </p>
                    <textarea 
                        name="review" 
                        id="review" 
                        rows="8" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent resize-none"
                        placeholder="Contoh: Properti sangat bagus dan bersih, lokasinya strategis dekat dengan pusat kota. Pemilik sangat ramah dan responsif..."
                        required>{{ old('review') }}</textarea>
                    <div class="flex justify-between items-center mt-2">
                        <p class="text-sm text-gray-500">
                            <span id="charCount">0</span> karakter
                        </p>
                        @error('review')
                            <p class="text-red-600 text-sm">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Submit Buttons --}}
                <div class="flex gap-4">
                    <button 
                        type="submit" 
                        class="flex-1 bg-sky-600 hover:bg-sky-700 text-white font-semibold py-3 px-6 rounded-lg transition">
                        ⭐ Kirim Review
                    </button>
                    <a 
                        href="{{ route('penyewa.pemesanan.index') }}" 
                        class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-3 px-6 rounded-lg text-center transition">
                        Batal
                    </a>
                </div>
            </form>
        </div>

        {{-- Info Box --}}
        <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex items-start gap-3">
                <svg class="w-6 h-6 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div class="text-sm text-blue-800">
                    <p class="font-semibold mb-1">Catatan:</p>
                    <ul class="list-disc list-inside space-y-1">
                        <li>Review Anda akan langsung dipublikasikan</li>
                        <li>Berikan review yang jujur dan konstruktif</li>
                        <li>Review akan membantu penyewa lain dalam memilih properti</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Star Rating System
    let selectedRating = 0;
    const ratingText = ['', 'Sangat Buruk', 'Buruk', 'Cukup', 'Bagus', 'Sangat Bagus'];

    function setRating(rating) {
        selectedRating = rating;
        document.getElementById('ratingInput').value = rating;
        document.getElementById('ratingText').textContent = ratingText[rating] + ' (' + rating + ' bintang)';
        
        // Update all stars
        updateStarDisplay();
    }

    function updateStarDisplay() {
        const stars = document.querySelectorAll('.star-btn');
        stars.forEach((star, index) => {
            const starIcon = star.querySelector('.star-icon');
            if (index < selectedRating) {
                starIcon.classList.remove('text-gray-300');
                starIcon.classList.add('text-yellow-400');
            } else {
                starIcon.classList.remove('text-yellow-400');
                starIcon.classList.add('text-gray-300');
            }
        });
    }

    // Hover effect
    const starButtons = document.querySelectorAll('.star-btn');
    starButtons.forEach((btn, index) => {
        btn.addEventListener('mouseenter', function() {
            starButtons.forEach((b, i) => {
                const icon = b.querySelector('.star-icon');
                if (i <= index) {
                    icon.classList.add('text-yellow-400');
                    icon.classList.remove('text-gray-300');
                } else {
                    icon.classList.remove('text-yellow-400');
                    icon.classList.add('text-gray-300');
                }
            });
        });
    });

    // Reset stars on mouse leave
    document.getElementById('starRating').addEventListener('mouseleave', function() {
        updateStarDisplay();
    });

    // Character counter
    const textarea = document.getElementById('review');
    const charCount = document.getElementById('charCount');
    
    textarea.addEventListener('input', function() {
        charCount.textContent = this.value.length;
    });

    // Initialize character count
    charCount.textContent = textarea.value.length;

    // Form validation
    document.querySelector('form').addEventListener('submit', function(e) {
        if (selectedRating === 0) {
            e.preventDefault();
            alert('Silakan pilih rating bintang terlebih dahulu!');
            document.getElementById('starRating').scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    });
</script>
@endsection
