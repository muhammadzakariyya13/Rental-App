@extends('layouts.penyewa')

@section('title', 'Detail Kontrak #' . $pemesanan->id_pemesanan)

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 md:px-8">
        
        {{-- Header --}}
        <div class="mb-6">
            <a href="{{ route('penyewa.kontrak.index') }}" class="text-sky-600 hover:text-sky-700 font-medium inline-flex items-center mb-4">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali ke Daftar Kontrak
            </a>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Kontrak Sewa Properti</h1>
            <p class="text-gray-600">Nomor Kontrak: <span class="font-semibold">#{{ $pemesanan->id_pemesanan }}</span></p>
        </div>

        {{-- Download Button --}}
        <div class="mb-6">
            <a href="{{ route('penyewa.kontrak.download', $pemesanan->id_pemesanan) }}" 
               class="inline-block bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg transition font-medium shadow-lg">
                📥 Download Kontrak PDF
            </a>
        </div>

        {{-- Contract Content --}}
        <div class="bg-white rounded-xl shadow-lg p-8 md:p-12">
            @php
                $tanggal_mulai = $pemesanan->tanggal_pemesanan;
                $tanggal_selesai = $pemesanan->tanggal_pemesanan->copy()->addMonths($pemesanan->lama_sewa);
            @endphp

            {{-- Header --}}
            <div class="text-center mb-8 pb-6 border-b-2 border-gray-200">
                <h2 class="text-3xl font-bold text-gray-900 mb-2">SURAT PERJANJIAN SEWA MENYEWA</h2>
                <p class="text-lg text-gray-600">Properti: {{ $pemesanan->properti->nama }}</p>
                <p class="text-sm text-gray-500 mt-2">Nomor: {{ $pemesanan->transaction_id }}</p>
            </div>

            {{-- Pembukaan --}}
            <div class="mb-6 text-justify leading-relaxed">
                <p class="mb-4">Pada hari ini, <strong>{{ $pemesanan->paid_at->isoFormat('dddd, D MMMM Y') }}</strong>, yang bertanda tangan di bawah ini:</p>
            </div>

            {{-- Pihak Pertama --}}
            <div class="mb-6">
                <h3 class="font-bold text-lg text-gray-900 mb-3">PIHAK PERTAMA (PEMILIK):</h3>
                <div class="bg-gray-50 rounded-lg p-4 space-y-2">
                    <div class="grid grid-cols-3">
                        <span class="text-gray-600">Nama</span>
                        <span class="col-span-2 font-semibold">: {{ $pemesanan->properti->pemilik->username ?? $pemesanan->properti->pemilik->email ?? 'Pemilik Properti' }}</span>
                    </div>
                    <div class="grid grid-cols-3">
                        <span class="text-gray-600">Email</span>
                        <span class="col-span-2 font-semibold">: {{ $pemesanan->properti->pemilik->email ?? '-' }}</span>
                    </div>
                    <div class="grid grid-cols-3">
                        <span class="text-gray-600">Telepon</span>
                        <span class="col-span-2 font-semibold">: {{ $pemesanan->properti->pemilik->phone_number ?? '-' }}</span>
                    </div>
                </div>
                <p class="mt-2 text-sm text-gray-600">Selanjutnya disebut sebagai <strong>PIHAK PERTAMA</strong></p>
            </div>

            {{-- Pihak Kedua --}}
            <div class="mb-6">
                <h3 class="font-bold text-lg text-gray-900 mb-3">PIHAK KEDUA (PENYEWA):</h3>
                <div class="bg-gray-50 rounded-lg p-4 space-y-2">
                    <div class="grid grid-cols-3">
                        <span class="text-gray-600">Nama</span>
                        <span class="col-span-2 font-semibold">: {{ $pemesanan->akun->username ?? $pemesanan->akun->email }}</span>
                    </div>
                    <div class="grid grid-cols-3">
                        <span class="text-gray-600">Email</span>
                        <span class="col-span-2 font-semibold">: {{ $pemesanan->akun->email }}</span>
                    </div>
                    <div class="grid grid-cols-3">
                        <span class="text-gray-600">Telepon</span>
                        <span class="col-span-2 font-semibold">: {{ $pemesanan->akun->phone_number ?? '-' }}</span>
                    </div>
                </div>
                <p class="mt-2 text-sm text-gray-600">Selanjutnya disebut sebagai <strong>PIHAK KEDUA</strong></p>
            </div>

            {{-- Pasal 1 --}}
            <div class="mb-6">
                <h3 class="font-bold text-lg text-gray-900 mb-3">PASAL 1 - OBJEK SEWA</h3>
                <div class="bg-sky-50 rounded-lg p-4">
                    <p class="mb-2">PIHAK PERTAMA sepakat untuk menyewakan properti kepada PIHAK KEDUA dengan detail sebagai berikut:</p>
                    <div class="mt-3 space-y-2">
                        <div class="grid grid-cols-3">
                            <span class="text-gray-600">Nama Properti</span>
                            <span class="col-span-2 font-semibold">: {{ $pemesanan->properti->nama }}</span>
                        </div>
                        <div class="grid grid-cols-3">
                            <span class="text-gray-600">Tipe</span>
                            <span class="col-span-2 font-semibold">: {{ ucfirst($pemesanan->properti->tipe) }}</span>
                        </div>
                        <div class="grid grid-cols-3">
                            <span class="text-gray-600">Alamat</span>
                            <span class="col-span-2 font-semibold">: {{ $pemesanan->properti->alamat }}</span>
                        </div>
                        @if($pemesanan->properti->kamar_tidur)
                        <div class="grid grid-cols-3">
                            <span class="text-gray-600">Kamar Tidur</span>
                            <span class="col-span-2 font-semibold">: {{ $pemesanan->properti->kamar_tidur }} kamar</span>
                        </div>
                        @endif
                        @if($pemesanan->properti->kamar_mandi)
                        <div class="grid grid-cols-3">
                            <span class="text-gray-600">Kamar Mandi</span>
                            <span class="col-span-2 font-semibold">: {{ $pemesanan->properti->kamar_mandi }} kamar</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Pasal 2 --}}
            <div class="mb-6">
                <h3 class="font-bold text-lg text-gray-900 mb-3">PASAL 2 - JANGKA WAKTU SEWA</h3>
                <div class="bg-sky-50 rounded-lg p-4">
                    <p class="mb-3">Jangka waktu sewa yang disepakati adalah sebagai berikut:</p>
                    <div class="space-y-2">
                        <div class="grid grid-cols-3">
                            <span class="text-gray-600">Tanggal Mulai</span>
                            <span class="col-span-2 font-semibold">: {{ $tanggal_mulai->format('d F Y') }}</span>
                        </div>
                        <div class="grid grid-cols-3">
                            <span class="text-gray-600">Tanggal Berakhir</span>
                            <span class="col-span-2 font-semibold">: {{ $tanggal_selesai->format('d F Y') }}</span>
                        </div>
                        <div class="grid grid-cols-3">
                            <span class="text-gray-600">Durasi</span>
                            <span class="col-span-2 font-semibold">: {{ $pemesanan->lama_sewa }} Bulan</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Pasal 3 --}}
            <div class="mb-6">
                <h3 class="font-bold text-lg text-gray-900 mb-3">PASAL 3 - HARGA SEWA DAN PEMBAYARAN</h3>
                <div class="bg-sky-50 rounded-lg p-4">
                    <div class="space-y-2">
                        <div class="grid grid-cols-3">
                            <span class="text-gray-600">Harga per Bulan</span>
                            <span class="col-span-2 font-semibold">: Rp {{ number_format($pemesanan->properti->harga, 0, ',', '.') }}</span>
                        </div>
                        <div class="grid grid-cols-3">
                            <span class="text-gray-600">Total Pembayaran</span>
                            <span class="col-span-2 font-semibold text-sky-600 text-lg">: Rp {{ number_format($pemesanan->total_harga, 0, ',', '.') }}</span>
                        </div>
                        <div class="grid grid-cols-3">
                            <span class="text-gray-600">Status Pembayaran</span>
                            <span class="col-span-2 font-semibold text-green-600">: LUNAS</span>
                        </div>
                        <div class="grid grid-cols-3">
                            <span class="text-gray-600">Tanggal Pembayaran</span>
                            <span class="col-span-2 font-semibold">: {{ $pemesanan->paid_at->format('d F Y, H:i') }} WIB</span>
                        </div>
                        <div class="grid grid-cols-3">
                            <span class="text-gray-600">Metode Pembayaran</span>
                            <span class="col-span-2 font-semibold">: {{ $pemesanan->formatted_payment_type }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Pasal 4 --}}
            <div class="mb-6">
                <h3 class="font-bold text-lg text-gray-900 mb-3">PASAL 4 - HAK DAN KEWAJIBAN</h3>
                <div class="bg-gray-50 rounded-lg p-4 text-sm text-gray-700 space-y-3">
                    <div>
                        <p class="font-semibold mb-2">Hak PIHAK KEDUA:</p>
                        <ul class="list-disc ml-5 space-y-1">
                            <li>Menempati dan menggunakan properti sesuai fungsinya</li>
                            <li>Mendapatkan properti dalam kondisi layak huni</li>
                            <li>Menerima perbaikan dari PIHAK PERTAMA jika terjadi kerusakan</li>
                        </ul>
                    </div>
                    <div>
                        <p class="font-semibold mb-2">Kewajiban PIHAK KEDUA:</p>
                        <ul class="list-disc ml-5 space-y-1">
                            <li>Menjaga dan merawat properti dengan baik</li>
                            <li>Tidak mengubah struktur bangunan tanpa izin tertulis</li>
                            <li>Mengembalikan properti dalam kondisi baik saat kontrak berakhir</li>
                            <li>Membayar tagihan listrik, air, dan utilitas lainnya</li>
                        </ul>
                    </div>
                </div>
            </div>

            {{-- Footer --}}
            <div class="mt-12 pt-6 border-t-2 border-gray-200">
                <p class="text-sm text-gray-600 mb-8">
                    Demikian surat perjanjian ini dibuat dengan sebenarnya untuk dapat dipergunakan sebagaimana mestinya.
                </p>
                
                <div class="grid grid-cols-2 gap-8 text-center">
                    <div>
                        <p class="font-semibold mb-16">PIHAK PERTAMA</p>
                        <p class="font-bold border-t-2 border-gray-900 inline-block px-8 pt-2">
                            {{ $pemesanan->properti->pemilik->username ?? $pemesanan->properti->pemilik->email ?? 'Pemilik Properti' }}
                        </p>
                    </div>
                    <div>
                        <p class="font-semibold mb-16">PIHAK KEDUA</p>
                        <p class="font-bold border-t-2 border-gray-900 inline-block px-8 pt-2">
                            {{ $pemesanan->akun->username ?? $pemesanan->akun->email }}
                        </p>
                    </div>
                </div>

                <div class="mt-8 text-center text-xs text-gray-500">
                    <p>Dokumen ini dibuat secara elektronik dan sah tanpa tanda tangan basah</p>
                    <p class="mt-1">Dicetak pada: {{ now()->format('d F Y, H:i') }} WIB</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
