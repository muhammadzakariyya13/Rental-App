{{-- filepath: D:\PROJEK LARAVEL\Rental-App\resources\views\pemilik\bookings\show.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div class="flex items-center space-x-4">
                <a href="{{ route('pemilik.bookings.index') }}" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </a>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Detail Booking #{{ $booking->id_sewa }}
                </h2>
            </div>
            <div class="flex items-center space-x-2">
                <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full {{ 
                    $booking->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                    ($booking->status === 'diterima' ? 'bg-green-100 text-green-800' : 
                    ($booking->status === 'ditolak' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800')) 
                }}">
                    {{ ucfirst($booking->status) }}
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
                                    <div class="h-16 w-16 rounded-full bg-gray-300 flex items-center justify-center">
                                        <span class="text-xl font-medium text-gray-700">
                                            {{ strtoupper(substr($booking->penyewa->username ?? 'U', 0, 1)) }}
                                        </span>
                                    </div>
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
                                    @if($booking->properti->primaryImage())
                                        <img src="{{ $booking->properti->primaryImage()->image_url }}" 
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
                                        <p class="text-sm"><span class="font-medium">Check-in:</span> {{ $booking->tanggal_mulai->format('d F Y') }}</p>
                                        <p class="text-sm"><span class="font-medium">Check-out:</span> {{ $booking->tanggal_selesai->format('d F Y') }}</p>
                                        <p class="text-sm"><span class="font-medium">Durasi:</span> {{ $booking->tanggal_mulai->diffInDays($booking->tanggal_selesai) }} hari</p>
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
                    @if($booking->status === 'pending')
                        <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
                            <div class="px-6 py-4 border-b border-gray-200">
                                <h3 class="text-lg font-medium text-gray-900">Aksi Booking</h3>
                            </div>
                            <div class="px-6 py-4 space-y-3">
                                <form method="POST" action="{{ route('pemilik.bookings.updateStatus', $booking->id_sewa) }}">
                                    @csrf
                                    <input type="hidden" name="status" value="diterima">
                                    <button type="submit" class="w-full bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded transition duration-200">
                                        Terima Booking
                                    </button>
                                </form>
                                
                                <form method="POST" action="{{ route('pemilik.bookings.updateStatus', $booking->id_sewa) }}">
                                    @csrf
                                    <input type="hidden" name="status" value="ditolak">
                                    <button type="submit" class="w-full bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded transition duration-200" 
                                            onclick="return confirm('Yakin ingin menolak booking ini?')">
                                        Tolak Booking
                                    </button>
                                </form>
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
                                <ul class="-mb-8">
                                    <li>
                                        <div class="relative pb-8">
                                            <div class="relative flex space-x-3">
                                                <div>
                                                    <span class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center ring-8 ring-white">
                                                        <svg class="h-4 w-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                                        </svg>
                                                    </span>
                                                </div>
                                                <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                    <div>
                                                        <p class="text-sm text-gray-500">Booking dibuat</p>
                                                    </div>
                                                    <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                                        {{ $booking->created_at->format('d/m/Y H:i') }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    
                                    @if($booking->tanggal_diterima)
                                    <li>
                                        <div class="relative">
                                            <div class="relative flex space-x-3">
                                                <div>
                                                    <span class="h-8 w-8 rounded-full bg-green-500 flex items-center justify-center ring-8 ring-white">
                                                        <svg class="h-4 w-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                        </svg>
                                                    </span>
                                                </div>
                                                <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                    <div>
                                                        <p class="text-sm text-gray-500">Booking {{ $booking->status === 'diterima' ? 'diterima' : 'ditolak' }}</p>
                                                    </div>
                                                    <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                                        {{ $booking->tanggal_diterima->format('d/m/Y H:i') }}
                                                    </div>
                                                </div>
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
                                <p class="text-sm"><span class="font-medium">ID Booking:</span> #{{ $booking->id_sewa }}</p>
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