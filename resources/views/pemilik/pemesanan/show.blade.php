<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div class="flex items-center space-x-4">
                <a href="{{ route('pemilik.bookings.index') }}" class="text-white hover:text-gray-200 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </a>
                <h2 class="font-semibold text-xl text-white leading-tight">
                    Detail Booking 
                </h2>
            </div>
            <div class="flex items-center space-x-2">
                <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full {{ 
                    $booking->status_pemesanan === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                    ($booking->status_pemesanan === 'confirmed' ? 'bg-green-100 text-green-800' : 
                    ($booking->status_pemesanan === 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800')) 
                }}">
                    {{ $booking->status_pemesanan === 'confirmed' ? 'Lunas' : ($booking->status_pemesanan === 'pending' ? 'Pending' : ($booking->status_pemesanan === 'cancelled' ? 'Dibatalkan' : ucfirst($booking->status_pemesanan))) }}
                </span>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Alert Messages -->
            @if(session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <!-- Detail Booking -->
                <div class="lg:col-span-2 space-y-6">
                    
                    <!-- Info Pemesan -->
                    <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900">Informasi Pemesan</h3>
                        </div>
                        <div class="px-6 py-4">
                            <div class="flex items-center space-x-4">
                                <div class="flex-shrink-0 h-16 w-16">
                                    @if($booking->penyewa && $booking->penyewa->profile_photo)
                                        @php
                                            $profilePhoto = $booking->penyewa->profile_photo;
                                            // Cek apakah sudah base64 atau path file
                                            if (str_starts_with($profilePhoto, 'data:image')) {
                                                $profileSrc = $profilePhoto;
                                            } elseif (str_contains($profilePhoto, 'base64,')) {
                                                $profileSrc = 'data:image/jpeg;base64,' . $profilePhoto;
                                            } else {
                                                // Path file, gunakan storage URL
                                                $profileSrc = asset('storage/' . $profilePhoto);
                                            }
                                        @endphp
                                        <img src="{{ $profileSrc }}" alt="Profile" class="h-16 w-16 rounded-full object-cover">
                                    @else
                                        <div class="h-16 w-16 rounded-full bg-gradient-to-r from-blue-500 to-purple-500 flex items-center justify-center">
                                            <span class="text-xl font-medium text-white">
                                                {{ strtoupper(substr($booking->penyewa->username ?? 'U', 0, 1)) }}
                                            </span>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <h4 class="text-lg font-medium text-gray-900">{{ $booking->penyewa->username ?? 'User' }}</h4>
                                    <p class="text-sm text-gray-500">{{ $booking->penyewa->email ?? '' }}</p>
                                    <p class="text-sm text-gray-500">{{ $booking->penyewa->phone_number ?? 'No. HP tidak tersedia' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Info Properti -->
                    <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900">Detail Properti</h3>
                        </div>
                        <div class="px-6 py-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <h4 class="text-lg font-medium text-gray-900">{{ $booking->properti->nama }}</h4>
                                    <p class="text-sm text-gray-600 mt-1">{{ $booking->properti->alamat }}</p>
                                    <div class="mt-3 space-y-1">
                                        <p class="text-sm"><span class="font-medium">Tipe:</span> {{ $booking->properti->tipe }}</p>
                                        <p class="text-sm"><span class="font-medium">Kamar Tidur:</span> {{ $booking->properti->kamar_tidur }}</p>
                                        <p class="text-sm"><span class="font-medium">Kamar Mandi:</span> {{ $booking->properti->kamar_mandi }}</p>
                                    </div>
                                </div>
                                <div>
                                    @if($booking->properti->gambar)
                                        @php
                                            $imageSrc = $booking->properti->gambar;
                                            if (!str_starts_with($imageSrc, 'data:image')) {
                                                $imageSrc = 'data:image/jpeg;base64,' . $imageSrc;
                                            }
                                        @endphp
                                        <img src="{{ $imageSrc }}" 
                                             alt="{{ $booking->properti->nama }}" 
                                             class="w-full h-32 object-cover rounded-lg">
                                    @else
                                        <div class="w-full h-32 bg-gray-200 rounded-lg flex items-center justify-center">
                                            <span class="text-gray-500">No Image</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Detail Pemesanan -->
                    <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900">Detail Pemesanan</h3>
                        </div>
                        <div class="px-6 py-4">
                            <div class="grid grid-cols-2 gap-6">
                                <div>
                                    <h4 class="font-medium text-gray-900 mb-2">Periode Sewa</h4>
                                    <div class="space-y-2">
                                        <p class="text-sm"><span class="font-medium">Tanggal Pemesanan:</span> {{ $booking->tanggal_pemesanan->format('d F Y') }}</p>
                                        <p class="text-sm"><span class="font-medium">Lama Sewa:</span> {{ $booking->lama_sewa }} bulan</p>
                                        @php
                                            $tanggalSelesai = $booking->tanggal_pemesanan->copy()->addMonths($booking->lama_sewa);
                                        @endphp
                                        <p class="text-sm"><span class="font-medium">Berakhir:</span> {{ $tanggalSelesai->format('d F Y') }}</p>
                                    </div>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-900 mb-2">Informasi Pembayaran</h4>
                                    <div class="space-y-2">
                                        <p class="text-sm"><span class="font-medium">Harga per bulan:</span> Rp {{ number_format($booking->properti->harga, 0, ',', '.') }}</p>
                                        <p class="text-lg font-bold text-green-600">Total: Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Aksi & Timeline -->
                <div class="space-y-6">
                    
                    <!-- Status Actions -->
                    @if($booking->status_pemesanan === 'pending')
                        <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
                            <div class="px-6 py-4 border-b border-gray-200">
                                <h3 class="text-lg font-medium text-gray-900">Aksi Booking</h3>
                            </div>
                            <div class="px-6 py-4 space-y-3">
                                <form method="POST" action="{{ route('pemilik.bookings.updateStatus', $booking->id_pemesanan) }}">
                                    @csrf
                                    <input type="hidden" name="status" value="confirmed">
                                    <button type="submit" class="w-full bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded transition duration-200">
                                        Terima Booking
                                    </button>
                                </form>
                                
                                <form method="POST" action="{{ route('pemilik.bookings.updateStatus', $booking->id_pemesanan) }}">
                                    @csrf
                                    <input type="hidden" name="status" value="cancelled">
                                    <button type="submit" class="w-full bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded transition duration-200" 
                                            onclick="return confirm('Yakin ingin menolak booking ini?')">
                                        Tolak Booking
                                    </button>
                                </form>
                            </div>
                        </div>
                    @elseif($booking->status_pemesanan === 'confirmed')
                        <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
                            <div class="px-6 py-4 border-b border-gray-200">
                                <h3 class="text-lg font-medium text-gray-900">Aksi Booking</h3>
                            </div>
                            <div class="px-6 py-4">
                                <form method="POST" action="{{ route('pemilik.bookings.updateStatus', $booking->id_pemesanan) }}">
                                    @csrf
                                    <input type="hidden" name="status" value="cancelled">
                                    <button type="submit" class="w-full bg-orange-500 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded transition duration-200" 
                                            onclick="return confirm('Yakin ingin membatalkan booking ini?{{ $booking->status_pembayaran === 'sudah_bayar' ? ' Uang akan dikembalikan ke penyewa melalui Midtrans.' : '' }}')">
                                        🔄 Batalkan Booking{{ $booking->status_pembayaran === 'sudah_bayar' ? ' & Refund' : '' }}
                                    </button>
                                </form>
                                @if($booking->status_pembayaran === 'sudah_bayar')
                                    <p class="mt-2 text-xs text-gray-500 text-center">
                                        * Refund akan diproses otomatis ke Midtrans
                                    </p>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Timeline -->
                    <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900">Timeline</h3>
                        </div>
                        <div class="px-6 py-4">
                            <div class="flow-root">
                                <ul class="space-y-4">
                                    <li class="relative">
                                        <div class="flex items-start space-x-3">
                                            <div class="flex-shrink-0">
                                                <span class="h-10 w-10 rounded-full bg-blue-500 flex items-center justify-center ring-4 ring-white shadow">
                                                    <svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                                    </svg>
                                                </span>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-center justify-between">
                                                    <p class="text-sm font-medium text-gray-900">Booking dibuat</p>
                                                    <p class="text-sm text-gray-500">{{ $booking->created_at->format('d/m/Y H:i') }}</p>
                                                </div>
                                                <p class="mt-1 text-sm text-gray-500">Pemesanan telah berhasil dibuat</p>
                                            </div>
                                        </div>
                                    </li>
                                    
                                    @if($booking->paid_at)
                                    <li class="relative">
                                        <div class="flex items-start space-x-3">
                                            <div class="flex-shrink-0">
                                                <span class="h-10 w-10 rounded-full bg-green-500 flex items-center justify-center ring-4 ring-white shadow">
                                                    <svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                    </svg>
                                                </span>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-center justify-between">
                                                    <p class="text-sm font-medium text-gray-900">Pembayaran {{ $booking->status_pemesanan === 'confirmed' ? 'berhasil' : 'diproses' }}</p>
                                                    <p class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($booking->paid_at)->format('d/m/Y H:i') }}</p>
                                                </div>
                                                <p class="mt-1 text-sm text-gray-500">Pembayaran telah dikonfirmasi</p>
                                            </div>
                                        </div>
                                    </li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Info Tambahan -->
                    <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900">Informasi Tambahan</h3>
                        </div>
                        <div class="px-6 py-4">
                            <div class="space-y-2">
                                <p class="text-sm"><span class="font-medium">ID Booking:</span> #{{ $booking->id_pemesanan }}</p>
                                <p class="text-sm"><span class="font-medium">Dibuat:</span> {{ $booking->created_at->diffForHumans() }}</p>
                                <p class="text-sm"><span class="font-medium">Last Update:</span> {{ $booking->updated_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>