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

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-6 gap-4 mb-6">
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
                                    <dt class="text-sm font-medium text-white truncate">Diterima</dt>
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
                                    <dt class="text-sm font-medium text-white truncate">Ditolak</dt>
                                    <dd class="text-lg font-medium text-white" id="stat-ditolak">{{ $bookingDitolak ?? 0 }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-gray-500 to-gray-600 overflow-hidden shadow rounded-lg">
                    <div class="p-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-3 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-white truncate">Expired</dt>
                                    <dd class="text-lg font-medium text-white">{{ $bookingExpired ?? 0 }}</dd>
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
                <form method="GET" class="grid grid-cols-1 md:grid-cols-6 gap-4" id="filterForm">
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
                            <option value="diterima" {{ request('status') == 'diterima' ? 'selected' : '' }}>Diterima</option>
                            <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                            <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
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
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Dari Tanggal</label>
                        <input type="date" name="date_from" value="{{ request('date_from') }}" 
                               class="w-full rounded-md border-gray-300 text-sm">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Sampai Tanggal</label>
                        <input type="date" name="date_to" value="{{ request('date_to') }}" 
                               class="w-full rounded-md border-gray-300 text-sm">
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

            <!-- Bulk Actions & Export -->
            <div class="mb-4 flex justify-between items-center">
                <div class="flex space-x-2" id="bulkActions" style="display: none;">
                    <span class="text-sm text-gray-600">
                        <span id="selectedCount">0</span> item dipilih
                    </span>
                    <button onclick="bulkAction('diterima')" class="bg-green-500 text-white px-3 py-1 rounded text-sm hover:bg-green-600 transition">
                        ✓ Bulk Terima
                    </button>
                    <button onclick="bulkAction('ditolak')" class="bg-red-500 text-white px-3 py-1 rounded text-sm hover:bg-red-600 transition">
                        ✗ Bulk Tolak
                    </button>
                    <button onclick="clearSelection()" class="bg-gray-500 text-white px-3 py-1 rounded text-sm hover:bg-gray-600 transition">
                        Clear
                    </button>
                </div>
                <div class="flex space-x-2">
                    <a href="{{ route('pemilik.bookings.export') }}?{{ request()->getQueryString() }}" 
                       class="bg-green-600 text-white px-4 py-2 rounded text-sm hover:bg-green-700 transition">
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
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            <input type="checkbox" id="selectAll" class="rounded" onchange="toggleSelectAll()">
                                        </th>
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
                                            <input type="checkbox" class="rounded booking-checkbox" 
                                                   value="{{ $booking->id_sewa }}" onchange="updateBulkActions()">
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                                        <span class="text-sm font-medium text-gray-700">
                                                            {{ strtoupper(substr($booking->penyewa->username ?? 'U', 0, 1)) }}
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">{{ $booking->penyewa->username ?? 'User' }}</div>
                                                    <div class="text-sm text-gray-500">{{ $booking->penyewa->email ?? '' }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $booking->properti->nama }}</div>
                                            <div class="text-sm text-gray-500">{{ Str::limit($booking->properti->alamat, 30) }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $booking->tanggal_mulai->format('d M Y') }}</div>
                                            <div class="text-sm text-gray-500">s/d {{ $booking->tanggal_selesai->format('d M Y') }}</div>
                                            @if($booking->tanggal_selesai < now() && $booking->status == 'diterima')
                                                <span class="text-xs text-red-600 font-medium">Expired</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ 
                                                $booking->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                                ($booking->status === 'diterima' ? 'bg-green-100 text-green-800' : 
                                                ($booking->status === 'ditolak' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800')) 
                                            }}">
                                                {{ ucfirst($booking->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex space-x-2">
                                                <a href="{{ route('pemilik.bookings.show', $booking->id_sewa) }}" 
                                                   class="text-indigo-600 hover:text-indigo-900 transition">Detail</a>
                                                
                                                @if($booking->status === 'pending')
                                                    <form action="{{ route('pemilik.bookings.updateStatus', $booking->id_sewa) }}" method="POST" class="inline">
                                                        @csrf
                                                        <input type="hidden" name="status" value="diterima">
                                                        <button type="submit" class="text-green-600 hover:text-green-900 transition">Terima</button>
                                                    </form>
                                                    <form action="{{ route('pemilik.bookings.updateStatus', $booking->id_sewa) }}" method="POST" class="inline">
                                                        @csrf
                                                        <input type="hidden" name="status" value="ditolak">
                                                        <button type="submit" class="text-red-600 hover:text-red-900 transition"
                                                                onclick="return confirm('Yakin ingin menolak booking ini?')">Tolak</button>
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
        function toggleSelectAll() {
            const selectAll = document.getElementById('selectAll');
            const checkboxes = document.querySelectorAll('.booking-checkbox');
            
            checkboxes.forEach(checkbox => {
                checkbox.checked = selectAll.checked;
            });
            
            updateBulkActions();
        }

        function updateBulkActions() {
            const checkedBoxes = document.querySelectorAll('.booking-checkbox:checked');
            const bulkActions = document.getElementById('bulkActions');
            const selectedCount = document.getElementById('selectedCount');
            
            if (checkedBoxes.length > 0) {
                bulkActions.style.display = 'flex';
                selectedCount.textContent = checkedBoxes.length;
            } else {
                bulkActions.style.display = 'none';
            }
        }

        function clearSelection() {
            document.getElementById('selectAll').checked = false;
            document.querySelectorAll('.booking-checkbox').forEach(cb => cb.checked = false);
            updateBulkActions();
        }

        async function bulkAction(status) {
            const checkedBoxes = document.querySelectorAll('.booking-checkbox:checked');
            const ids = Array.from(checkedBoxes).map(cb => cb.value);
            
            if (ids.length === 0) {
                alert('Pilih minimal 1 booking untuk diupdate');
                return;
            }

            if (!confirm(`Yakin ingin mengubah ${ids.length} booking ke status ${status}?`)) {
                return;
            }

            try {
                const response = await fetch('{{ route("pemilik.bookings.bulkUpdate") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        ids: ids,
                        status: status
                    })
                });

                const result = await response.json();
                
                if (result.success) {
                    alert(result.message);
                    window.location.reload();
                } else {
                    alert('Terjadi kesalahan');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat memproses');
            }
        }

        async function refreshStats() {
            try {
                const response = await fetch('{{ route("pemilik.bookings.stats") }}');
                const stats = await response.json();
                
                document.getElementById('stat-total').textContent = stats.total;
                document.getElementById('stat-pending').textContent = stats.pending;
                document.getElementById('stat-diterima').textContent = stats.diterima;
                document.getElementById('stat-ditolak').textContent = stats.ditolak;
                
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