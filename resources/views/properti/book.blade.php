@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6 max-w-4xl">
    <!-- Breadcrumb -->
    <nav class="flex mb-6" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('properti.index') }}" class="text-blue-600 hover:text-blue-800">
                    🏠 Properti
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    <a href="{{ route('properti.show', $properti->id_properti) }}" class="ml-1 text-blue-600 hover:text-blue-800 md:ml-2">{{ $properti->nama }}</a>
                </div>
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="ml-1 text-gray-500 md:ml-2">Booking</span>
                </div>
            </li>
        </ol>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Booking Form -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h1 class="text-2xl font-bold text-gray-800 mb-6">🛒 Booking Properti</h1>
                
                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                        {{ session('error') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                        <ul class="list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Booking Form -->
                <form action="{{ route('properti.processBooking', $properti->id_properti) }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <!-- Customer Info -->
                    <div>
                        <h3 class="text-lg font-semibold mb-4">Informasi Penyewa</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="nama" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap *</label>
                                <input type="text" id="nama" name="nama" value="{{ old('nama', auth()->user()->nama) }}" required
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                                <input type="email" id="email" name="email" value="{{ old('email', auth()->user()->email) }}" required
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">No. Telepon *</label>
                                <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" required
                                       placeholder="Masukkan nomor telepon Anda"
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>
                    </div>

                    <!-- Rental Period -->
                    <div>
                        <h3 class="text-lg font-semibold mb-4">Periode Sewa</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="tanggal_mulai" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai *</label>
                                <input type="date" id="tanggal_mulai" name="tanggal_mulai" value="{{ old('tanggal_mulai') }}" required min="{{ date('Y-m-d') }}"
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label for="tanggal_selesai" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Selesai *</label>
                                <input type="date" id="tanggal_selesai" name="tanggal_selesai" value="{{ old('tanggal_selesai') }}" required
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                <div class="flex items-center">
                                    <span class="text-blue-600 text-xl mr-2">ℹ️</span>
                                    <div>
                                        <p class="text-blue-800 font-medium">Durasi Sewa: <span id="duration">0 bulan</span></p>
                                        <p class="text-blue-600 text-sm">Total Biaya: <span id="totalCost">Rp 0</span></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Notes -->
                    <div>
                        <label for="catatan" class="block text-sm font-medium text-gray-700 mb-2">Catatan Tambahan</label>
                        <textarea id="catatan" name="catatan" rows="4" 
                                  placeholder="Tambahkan permintaan khusus atau catatan untuk pemilik properti..."
                                  class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500">{{ old('catatan') }}</textarea>
                    </div>

                    <!-- Terms -->
                    <div>
                        <div class="flex items-start">
                            <input type="checkbox" id="terms" name="terms" required
                                   class="mt-1 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="terms" class="ml-2 text-sm text-gray-700">
                                Saya setuju dengan <a href="#" class="text-blue-600 hover:text-blue-800">syarat dan ketentuan</a> 
                                yang berlaku dan bersedia mengikuti aturan sewa properti.
                            </label>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex space-x-4">
                        <button type="submit" 
                                class="flex-1 bg-blue-600 text-white py-3 px-6 rounded-lg hover:bg-blue-700 transition duration-200 font-medium">
                            📝 Ajukan Booking
                        </button>
                        <a href="{{ route('properti.show', $properti->id_properti) }}" 
                           class="flex-1 bg-gray-600 text-white py-3 px-6 rounded-lg hover:bg-gray-700 transition duration-200 font-medium text-center">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Property Summary -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-md p-6 sticky top-6">
                <h3 class="text-lg font-semibold mb-4">Ringkasan Properti</h3>
                
                @if($properti->foto)
                    <img src="{{ asset('storage/' . $properti->foto) }}" alt="{{ $properti->nama }}" 
                         class="w-full h-40 object-cover rounded-lg mb-4">
                @else
                    <div class="w-full h-40 bg-gradient-to-br from-blue-100 to-blue-200 flex items-center justify-center rounded-lg mb-4">
                        <span class="text-blue-600 text-4xl">🏠</span>
                    </div>
                @endif

                <h4 class="font-semibold text-gray-800 mb-2">{{ $properti->nama }}</h4>
                <p class="text-gray-600 text-sm mb-4 line-clamp-3">{{ $properti->deskripsi }}</p>
                
                <div class="border-t pt-4">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-gray-600">Harga per bulan:</span>
                        <span class="font-semibold text-blue-600">Rp {{ number_format($properti->harga, 0, ',', '.') }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-gray-600">Status:</span>
                        <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">
                            {{ ucfirst($properti->status) }}
                        </span>
                    </div>
                </div>

                <div class="mt-4 pt-4 border-t">
                    <h5 class="font-semibold mb-2">Langkah Selanjutnya:</h5>
                    <ol class="text-sm text-gray-600 space-y-1">
                        <li>1. Isi formulir booking</li>
                        <li>2. Admin akan menghubungi Anda</li>
                        <li>3. Lakukan pembayaran</li>
                        <li>4. Kontrak disepakati</li>
                        <li>5. Kunci properti diserahkan</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tanggalMulai = document.getElementById('tanggal_mulai');
    const tanggalSelesai = document.getElementById('tanggal_selesai');
    const duration = document.getElementById('duration');
    const totalCost = document.getElementById('totalCost');
    const hargaPerBulan = {{ $properti->harga }};

    function calculateDuration() {
        const startDate = new Date(tanggalMulai.value);
        const endDate = new Date(tanggalSelesai.value);
        
        if (startDate && endDate && endDate > startDate) {
            const diffTime = Math.abs(endDate - startDate);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
            const months = Math.ceil(diffDays / 30);
            
            duration.textContent = months + ' bulan';
            totalCost.textContent = 'Rp ' + (hargaPerBulan * months).toLocaleString('id-ID');
        } else {
            duration.textContent = '0 bulan';
            totalCost.textContent = 'Rp 0';
        }
    }

    tanggalMulai.addEventListener('change', function() {
        // Set minimum end date to start date + 1 month
        const minEndDate = new Date(this.value);
        minEndDate.setMonth(minEndDate.getMonth() + 1);
        tanggalSelesai.min = minEndDate.toISOString().split('T')[0];
        calculateDuration();
    });

    tanggalSelesai.addEventListener('change', calculateDuration);
});
</script>

<style>
.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
@endsection