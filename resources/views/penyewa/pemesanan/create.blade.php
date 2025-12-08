@extends('layouts.penyewa')

@section('title', 'Pesan Properti - ' . $properti->nama)

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 md:px-8">
        
        {{-- Header --}}
        <div class="mb-6">
            <a href="{{ route('penyewa.browse.show', $properti->id_properti) }}" class="text-sky-600 hover:text-sky-700 flex items-center gap-2 mb-4">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali ke Detail Properti
            </a>
            <h1 class="text-3xl font-bold text-gray-900">Pesan Properti</h1>
            <p class="text-gray-600 mt-1">Lengkapi form booking dan lanjutkan ke pembayaran</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            
            {{-- Form Section --}}
            <div class="md:col-span-2">
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-6">Detail Pemesanan</h2>

                    @if(session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form action="{{ route('penyewa.pemesanan.store') }}" method="POST" id="bookingForm">
                        @csrf
                        <input type="hidden" name="id_properti" value="{{ $properti->id_properti }}">

                        {{-- Tanggal Mulai Sewa --}}
                        <div class="mb-6">
                            <label for="tanggal_mulai" class="block text-sm font-semibold text-gray-700 mb-2">
                                Tanggal Mulai Sewa <span class="text-red-500">*</span>
                            </label>
                            <input type="date" 
                                   id="tanggal_mulai" 
                                   name="tanggal_mulai" 
                                   min="{{ date('Y-m-d') }}"
                                   value="{{ old('tanggal_mulai', date('Y-m-d')) }}"
                                   required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent">
                            @error('tanggal_mulai')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Lama Sewa --}}
                        <div class="mb-6">
                            <label for="lama_sewa" class="block text-sm font-semibold text-gray-700 mb-2">
                                Lama Sewa (Bulan) <span class="text-red-500">*</span>
                            </label>
                            <select id="lama_sewa" 
                                    name="lama_sewa" 
                                    required
                                    onchange="calculateTotal()"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent">
                                <option value="">Pilih lama sewa</option>
                                @for($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}" {{ old('lama_sewa') == $i ? 'selected' : '' }}>
                                        {{ $i }} Bulan
                                    </option>
                                @endfor
                            </select>
                            @error('lama_sewa')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Total Harga Display --}}
                        <div class="bg-sky-50 border-2 border-sky-200 rounded-lg p-4 mb-6">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-gray-700">Harga per bulan:</span>
                                <span class="font-semibold text-gray-900">Rp {{ number_format($properti->harga, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-gray-700">Durasi sewa:</span>
                                <span class="font-semibold text-gray-900" id="durasiText">- bulan</span>
                            </div>
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-gray-700">Subtotal:</span>
                                <span class="font-semibold text-gray-900" id="subtotalHarga">Rp 0</span>
                            </div>
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-gray-700">Biaya Admin (0.5%):</span>
                                <span class="font-semibold text-gray-900" id="biayaAdmin">Rp 0</span>
                            </div>
                            <div class="border-t border-sky-300 pt-2 mt-2">
                                <div class="flex justify-between items-center">
                                    <span class="text-lg font-bold text-gray-900">Total Pembayaran:</span>
                                    <span class="text-2xl font-bold text-sky-600" id="totalHarga">Rp 0</span>
                                </div>
                            </div>
                        </div>

                        {{-- Ketentuan --}}
                        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-yellow-700 font-semibold mb-1">Ketentuan Pemesanan:</p>
                                    <ul class="text-sm text-yellow-700 list-disc list-inside space-y-1">
                                        <li>Pembayaran dilakukan melalui Midtrans (Virtual Account, E-Wallet, dll)</li>
                                        <li>Setelah pembayaran berhasil, properti akan berstatus "Disewa"</li>
                                        <li>Pemilik akan menghubungi Anda untuk proses serah terima kunci</li>
                                        <li>Minimal sewa 1 bulan, maksimal 12 bulan</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        {{-- Checkbox Agreement --}}
                        <div class="mb-6">
                            <label class="flex items-start">
                                <input type="checkbox" 
                                       id="agreement" 
                                       required
                                       class="mt-1 h-4 w-4 text-sky-600 focus:ring-sky-500 border-gray-300 rounded">
                                <span class="ml-2 text-sm text-gray-700">
                                    Saya setuju dengan <a href="#" class="text-sky-600 hover:text-sky-700 font-medium">syarat dan ketentuan</a> yang berlaku
                                </span>
                            </label>
                        </div>

                        {{-- Submit Button --}}
                        <button type="submit" 
                                id="submitBtn"
                                disabled
                                class="w-full bg-sky-600 hover:bg-sky-700 disabled:bg-gray-300 disabled:cursor-not-allowed text-white font-bold py-4 rounded-lg transition shadow-lg">
                            🔒 Lanjutkan ke Pembayaran
                        </button>
                    </form>
                </div>
            </div>

            {{-- Property Summary --}}
            <div class="md:col-span-1">
                <div class="bg-white rounded-xl shadow-md p-6 sticky top-24">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Ringkasan Properti</h3>
                    
                    {{-- Image --}}
                    <div class="mb-4 rounded-lg overflow-hidden">
                        @if($properti->gambar)
                            <img src="{{ asset($properti->gambar) }}" 
                                 alt="{{ $properti->nama }}"
                                 class="w-full h-48 object-cover">
                        @else
                            <div class="w-full h-48 flex items-center justify-center bg-gradient-to-br from-sky-50 to-sky-100">
                                <span class="text-6xl">🏠</span>
                            </div>
                        @endif
                    </div>

                    {{-- Property Info --}}
                    <div>
                        <h4 class="font-bold text-gray-900 mb-2">{{ $properti->nama }}</h4>
                        <p class="text-sm text-gray-600 mb-3 flex items-start gap-2">
                            <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            {{ $properti->alamat }}
                        </p>

                        <div class="space-y-2 text-sm border-t pt-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Tipe:</span>
                                <span class="font-semibold text-gray-900 capitalize">{{ $properti->tipe ?? 'N/A' }}</span>
                            </div>
                            @if($properti->kamar_tidur)
                            <div class="flex justify-between">
                                <span class="text-gray-600">Kamar Tidur:</span>
                                <span class="font-semibold text-gray-900">{{ $properti->kamar_tidur }}</span>
                            </div>
                            @endif
                            @if($properti->kamar_mandi)
                            <div class="flex justify-between">
                                <span class="text-gray-600">Kamar Mandi:</span>
                                <span class="font-semibold text-gray-900">{{ $properti->kamar_mandi }}</span>
                            </div>
                            @endif
                            @if($properti->luas_bangunan)
                            <div class="flex justify-between">
                                <span class="text-gray-600">Luas Bangunan:</span>
                                <span class="font-semibold text-gray-900">{{ $properti->luas_bangunan }} m²</span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const hargaPerBulan = {{ $properti->harga }};
    
    function calculateTotal() {
        const lamaSewa = document.getElementById('lama_sewa').value;
        const submitBtn = document.getElementById('submitBtn');
        const agreement = document.getElementById('agreement');
        
        if (lamaSewa) {
            const subtotal = hargaPerBulan * lamaSewa;
            const biayaAdmin = subtotal * 0.005; // 0.5%
            const total = subtotal + biayaAdmin;
            
            document.getElementById('subtotalHarga').textContent = 'Rp ' + subtotal.toLocaleString('id-ID');
            document.getElementById('biayaAdmin').textContent = 'Rp ' + Math.round(biayaAdmin).toLocaleString('id-ID');
            document.getElementById('totalHarga').textContent = 'Rp ' + Math.round(total).toLocaleString('id-ID');
            document.getElementById('durasiText').textContent = lamaSewa + ' bulan';
            
            // Enable submit if agreement checked
            if (agreement.checked) {
                submitBtn.disabled = false;
            }
        } else {
            document.getElementById('subtotalHarga').textContent = 'Rp 0';
            document.getElementById('biayaAdmin').textContent = 'Rp 0';
            document.getElementById('totalHarga').textContent = 'Rp 0';
            document.getElementById('durasiText').textContent = '- bulan';
            submitBtn.disabled = true;
        }
    }

    // Check agreement
    document.getElementById('agreement').addEventListener('change', function() {
        const lamaSewa = document.getElementById('lama_sewa').value;
        const submitBtn = document.getElementById('submitBtn');
        
        if (this.checked && lamaSewa) {
            submitBtn.disabled = false;
        } else {
            submitBtn.disabled = true;
        }
    });

    // Initial calculation if old value exists
    window.addEventListener('load', function() {
        calculateTotal();
    });
</script>
@endsection
