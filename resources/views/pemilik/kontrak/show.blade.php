@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 md:px-8">
        
        {{-- Header --}}
        <div class="mb-6">
            <a href="{{ route('pemilik.kontrak.index') }}" class="text-sky-600 hover:text-sky-700 font-medium inline-flex items-center mb-4">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali ke Kelola Kontrak
            </a>
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">Detail Kontrak Sewa</h1>
                    <p class="text-gray-600">Nomor Kontrak: <span class="font-semibold">#{{ $pemesanan->id_pemesanan }}</span></p>
                </div>
                
                {{-- Izin Status Badge --}}
                @if($pemesanan->download_izin)
                    <span class="inline-flex items-center gap-2 px-4 py-2 bg-green-100 text-green-700 rounded-lg text-sm font-medium">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        Izin Download Diberikan
                    </span>
                @else
                    <span class="inline-flex items-center gap-2 px-4 py-2 bg-orange-100 text-orange-700 rounded-lg text-sm font-medium">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM7 9a1 1 0 000 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/>
                        </svg>
                        Belum Diberi Izin
                    </span>
                @endif
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="mb-6 flex gap-3">
            <a href="{{ route('pemilik.kontrak.download', $pemesanan->id_pemesanan) }}" 
               class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg transition font-medium shadow-lg inline-flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Download PDF
            </a>
            
            {{-- Toggle Permission Button --}}
            <form action="{{ route('pemilik.kontrak.toggle-permission', $pemesanan->id_pemesanan) }}" 
                  method="POST" 
                  onsubmit="return confirm('Yakin ingin {{ $pemesanan->download_izin ? 'mencabut' : 'memberikan' }} izin download untuk penyewa ini?')">
                @csrf
                @if($pemesanan->download_izin)
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-lg transition font-medium shadow-lg inline-flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                        </svg>
                        Cabut Izin Download
                    </button>
                @else
                    <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-lg transition font-medium shadow-lg inline-flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Berikan Izin Download
                    </button>
                @endif
            </form>
        </div>

        {{-- Permission Info Alert --}}
        @if(!$pemesanan->download_izin)
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6 rounded-lg">
                <div class="flex">
                    <svg class="w-6 h-6 text-yellow-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <div>
                        <p class="font-semibold text-yellow-800">Izin Belum Diberikan</p>
                        <p class="text-yellow-700 text-sm mt-1">Penyewa <strong>{{ $penyewa_nama }}</strong> belum bisa melihat atau mendownload kontrak ini. Klik tombol "Berikan Izin Download" untuk mengaktifkan akses.</p>
                    </div>
                </div>
            </div>
        @else
            <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-6 rounded-lg">
                <div class="flex">
                    <svg class="w-6 h-6 text-green-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <div>
                        <p class="font-semibold text-green-800">Izin Sudah Diberikan</p>
                        <p class="text-green-700 text-sm mt-1">Penyewa <strong>{{ $penyewa_nama }}</strong> sudah bisa melihat dan mendownload kontrak ini.</p>
                    </div>
                </div>
            </div>
        @endif

        {{-- Contract Preview (Same as penyewa/kontrak/show.blade.php) --}}
        <div class="bg-white rounded-xl shadow-lg p-8 md:p-12">
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
                        <span class="col-span-2 font-semibold">: {{ $pemilik_nama }}</span>
                    </div>
                    <div class="grid grid-cols-3">
                        <span class="text-gray-600">Email</span>
                        <span class="col-span-2 font-semibold">: {{ $pemesanan->properti->pemilik->email ?? '-' }}</span>
                    </div>
                    <div class="grid grid-cols-3">
                        <span class="text-gray-600">Telepon</span>
                        <span class="col-span-2 font-semibold">: {{ $pemilik_telepon }}</span>
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
                        <span class="col-span-2 font-semibold">: {{ $penyewa_nama }}</span>
                    </div>
                    <div class="grid grid-cols-3">
                        <span class="text-gray-600">Email</span>
                        <span class="col-span-2 font-semibold">: {{ $pemesanan->akun->email }}</span>
                    </div>
                    <div class="grid grid-cols-3">
                        <span class="text-gray-600">Telepon</span>
                        <span class="col-span-2 font-semibold">: {{ $penyewa_telepon }}</span>
                    </div>
                </div>
                <p class="mt-2 text-sm text-gray-600">Selanjutnya disebut sebagai <strong>PIHAK KEDUA</strong></p>
            </div>

            {{-- Pasal 1 - Objek Sewa --}}
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
                    </div>
                </div>
            </div>

            {{-- Pasal 2 - Jangka Waktu --}}
            <div class="mb-6">
                <h3 class="font-bold text-lg text-gray-900 mb-3">PASAL 2 - JANGKA WAKTU SEWA</h3>
                <div class="bg-sky-50 rounded-lg p-4">
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

            {{-- Pasal 3 - Pembayaran --}}
            <div class="mb-6">
                <h3 class="font-bold text-lg text-gray-900 mb-3">PASAL 3 - HARGA SEWA DAN PEMBAYARAN</h3>
                <div class="bg-sky-50 rounded-lg p-4">
                    <div class="space-y-2">
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
                    </div>
                </div>
            </div>

            {{-- Footer Signature --}}
            <div class="mt-12 pt-6 border-t-2 border-gray-200">
                <div class="grid grid-cols-2 gap-8 text-center">
                    <div>
                        <p class="font-semibold mb-16">PIHAK PERTAMA</p>
                        <p class="font-bold border-t-2 border-gray-900 inline-block px-8 pt-2">
                            {{ $pemilik_nama }}
                        </p>
                    </div>
                    <div>
                        <p class="font-semibold mb-16">PIHAK KEDUA</p>
                        <p class="font-bold border-t-2 border-gray-900 inline-block px-8 pt-2">
                            {{ $penyewa_nama }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
