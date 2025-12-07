{{-- filepath: d:\PROJEK LARAVEL\Rental-App\resources\views\pemilik\bookings\index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Kelola Booking
            </h2>
            <div class="flex space-x-2">
                <button onclick="refreshStats()" class="bg-blue-500 text-white px-3 py-1 rounded text-sm hover:bg-blue-600">
                    🔄 Refresh
                </button>
                <span class="bg-blue-100 text-blue-800 text-sm px-3 py-1 rounded-full">
                    {{ $bookings->total() }} Total
                </span>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    <span class="block sm:inline">{{ session('success') }}</span>
                    <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.style.display='none'">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <!-- Revenue Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-gradient-to-r from-emerald-500 to-emerald-600 overflow-hidden shadow rounded-lg">
                    <div class="p-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-3 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-white truncate">Total Pendapatan</dt>
                                    <dd class="text-lg font-medium text-white" id="stat-total-pendapatan">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-teal-500 to-teal-600 overflow-hidden shadow rounded-lg">
                    <div class="p-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div class="ml-3 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-white truncate">Pendapatan Bulan Ini</dt>
                                    <dd class="text-lg font-medium text-white" id="stat-pendapatan-bulan">Rp {{ number_format($pendapatanBulanIni, 0, ',', '.') }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Booking Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 overflow-hidden shadow rounded-lg">
                    <div class="p-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                            </div>
                            <div class="ml-3 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-white truncate">Total</dt>
                                    <dd class="text-lg font-medium text-white" id="stat-total">{{ $totalBooking }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 overflow-hidden shadow rounded-lg">
                    <div class="p-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-3 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-white truncate">Pending</dt>
                                    <dd class="text-lg font-medium text-white" id="stat-pending">{{ $bookingPending }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-green-500 to-green-600 overflow-hidden shadow rounded-lg">
                    <div class="p-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <div class="ml-3 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-white truncate">Lunas</dt>
                                    <dd class="text-lg font-medium text-white" id="stat-diterima">{{ $bookingDiterima }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-red-500 to-red-600 overflow-hidden shadow rounded-lg">
                    <div class="p-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </div>
                            <div class="ml-3 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-white truncate">Dibatalkan</dt>
                                    <dd class="text-lg font-medium text-white" id="stat-ditolak">{{ $bookingDitolak ?? 0 }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-purple-500 to-purple-600 overflow-hidden shadow rounded-lg">
                    <div class="p-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                            </div>
                            <div class="ml-3 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-white truncate">Bulan Ini</dt>
                                    <dd class="text-lg font-medium text-white">{{ $bookingBulanIni ?? 0 }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Advanced Filters -->
            <div class="mb-6 bg-white p-4 rounded-lg shadow">
                <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4" id="filterForm">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Cari Pemesan</label>
                        <input type="text" name="search" value="{{ request('search') }}" 
                               placeholder="Nama atau email..." 
                               class="w-full rounded-md border-gray-300 text-sm">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select name="status" class="w-full rounded-md border-gray-300 text-sm">
                            <option value="">Semua Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Lunas</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Properti</label>
                        <select name="properti" class="w-full rounded-md border-gray-300 text-sm">
                            <option value="">Semua Properti</option>
                            @foreach($propertiList as $prop)
                                <option value="{{ $prop->id_properti }}" {{ request('properti') == $prop->id_properti ? 'selected' : '' }}>
                                    {{ $prop->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="flex items-end space-x-2">
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded text-sm hover:bg-blue-600 transition">
                            🔍 Filter
                        </button>
                        <a href="{{ route('pemilik.bookings.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded text-sm hover:bg-gray-600 transition">
                            🔄 Reset
                        </a>
                    </div>
                </form>
            </div>

            <!-- Export Button -->
            <div class="mb-4 flex justify-end items-center">
                <div class="flex space-x-2">
                    <a href="{{ route('pemilik.bookings.export') }}" 
                       class="bg-green-600 text-white px-4 py-2 rounded text-sm hover:bg-green-700 transition flex items-center">
                        <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        📊 Export Excel
                    </a>
                </div>
            </div>

            <!-- Table -->
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
                <div class="p-6">
                    @if($bookings->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full table-auto">
                                <thead>
                                    <tr class="bg-gray-50">
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pemesan</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Properti</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Periode</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($bookings as $booking)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
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
                                                        <img src="{{ $profileSrc }}" alt="Profile" class="h-10 w-10 rounded-full object-cover">
                                                    @else
                                                        <div class="h-10 w-10 rounded-full bg-gradient-to-r from-blue-500 to-purple-500 flex items-center justify-center">
                                                            <span class="text-sm font-medium text-white">
                                                                {{ strtoupper(substr($booking->penyewa->username ?? 'U', 0, 1)) }}
                                                            </span>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">{{ $booking->penyewa->username ?? 'User' }}</div>
                                                    <div class="text-sm text-gray-500">{{ $booking->penyewa->email ?? '' }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-12 w-16 mr-3">
                                                    @if($booking->properti->gambar)
                                                        <img src="{{ asset($booking->properti->gambar) }}" alt="{{ $booking->properti->nama }}" class="h-12 w-16 object-cover rounded">
                                                    @else
                                                        <div class="h-12 w-16 bg-gray-200 rounded flex items-center justify-center">
                                                            <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                            </svg>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900">{{ $booking->properti->nama }}</div>
                                                    <div class="text-sm text-gray-500">{{ Str::limit($booking->properti->alamat, 30) }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $booking->tanggal_pemesanan->format('d M Y') }}</div>
                                            <div class="text-sm text-gray-500">{{ $booking->lama_sewa }} bulan</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ 
                                                $booking->status_pemesanan === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                                ($booking->status_pemesanan === 'confirmed' ? 'bg-green-100 text-green-800' : 
                                                ($booking->status_pemesanan === 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800')) 
                                            }}">
                                                {{ $booking->status_pemesanan === 'confirmed' ? 'Lunas' : ($booking->status_pemesanan === 'pending' ? 'Pending' : ($booking->status_pemesanan === 'cancelled' ? 'Dibatalkan' : ucfirst($booking->status_pemesanan))) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex space-x-2">
                                                <a href="{{ route('pemilik.bookings.show', $booking->id_pemesanan) }}" 
                                                   class="text-indigo-600 hover:text-indigo-900 transition">Detail</a>
                                                
                                                @if($booking->status_pemesanan === 'pending')
                                                    <form action="{{ route('pemilik.bookings.updateStatus', $booking->id_pemesanan) }}" method="POST" class="inline">
                                                        @csrf
                                                        <input type="hidden" name="status" value="confirmed">
                                                        <button type="submit" class="text-green-600 hover:text-green-900 transition">Terima</button>
                                                    </form>
                                                    <form action="{{ route('pemilik.bookings.updateStatus', $booking->id_pemesanan) }}" method="POST" class="inline">
                                                        @csrf
                                                        <input type="hidden" name="status" value="cancelled">
                                                        <button type="submit" class="text-red-600 hover:text-red-900 transition"
                                                                onclick="return confirm('Yakin ingin menolak booking ini?')">Tolak</button>
                                                    </form>
                                                @elseif($booking->status_pemesanan === 'confirmed')
                                                    <form action="{{ route('pemilik.bookings.updateStatus', $booking->id_pemesanan) }}" method="POST" class="inline">
                                                        @csrf
                                                        <input type="hidden" name="status" value="cancelled">
                                                        <button type="submit" class="text-orange-600 hover:text-orange-900 transition"
                                                                onclick="return confirm('Yakin ingin membatalkan booking ini? {{ $booking->status_pembayaran === 'sudah_bayar' ? 'Uang akan dikembalikan ke penyewa melalui Midtrans.' : '' }}')">Batalkan</button>
                                                    </form>
                                                @elseif($booking->status_pemesanan === 'cancelled')
                                                    <form action="{{ route('pemilik.bookings.destroy', $booking->id_pemesanan) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-900 transition"
                                                                onclick="return confirm('Yakin ingin menghapus booking ini? Data akan dihapus permanen.')">Hapus</button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-6">
                            {{ $bookings->appends(request()->query())->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada booking</h3>
                            <p class="mt-1 text-sm text-gray-500">Booking akan muncul di sini setelah ada penyewa yang memesan properti Anda.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript untuk fitur interaktif -->
    <script>
        async function refreshStats() {
            try {
                const response = await fetch('{{ route("pemilik.bookings.stats") }}');
                const stats = await response.json();
                
                // Update booking stats
                document.getElementById('stat-total').textContent = stats.total;
                document.getElementById('stat-pending').textContent = stats.pending;
                document.getElementById('stat-diterima').textContent = stats.diterima;
                document.getElementById('stat-ditolak').textContent = stats.ditolak;
                
                // Update revenue stats
                document.getElementById('stat-total-pendapatan').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(stats.totalPendapatan);
                document.getElementById('stat-pendapatan-bulan').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(stats.pendapatanBulanIni);
                
                // Show success message
                const successDiv = document.createElement('div');
                successDiv.className = 'fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded shadow z-50';
                successDiv.textContent = 'Stats berhasil di-refresh!';
                document.body.appendChild(successDiv);
                
                setTimeout(() => {
                    successDiv.remove();
                }, 3000);
                
            } catch (error) {
                console.error('Error refreshing stats:', error);
            }
        }
    </script>
</x-app-layout>