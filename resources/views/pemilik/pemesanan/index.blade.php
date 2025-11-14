{{-- filepath: D:\PROJEK LARAVEL\Rental-App\resources\views\pemilik\pemesanan\index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                💰 Dashboard Pendapatan & Analytics
            </h2>
            <div class="flex space-x-2">
                <button onclick="toggleView()" class="bg-blue-500 text-white px-3 py-1 rounded text-sm hover:bg-blue-600 transition">
                    📊 Toggle Analytics
                </button>
                <div class="text-sm text-gray-600">
                    Total: {{ $totalPemesanan }} pemesanan
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- EXISTING Stats Cards - TETAP SAMA -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <!-- Total Pemesanan -->
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 overflow-hidden shadow rounded-lg transform hover:scale-105 transition duration-300">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-white truncate">Total Pemesanan</dt>
                                    <dd class="text-lg font-medium text-white">{{ $totalPemesanan }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pending -->
                <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 overflow-hidden shadow rounded-lg transform hover:scale-105 transition duration-300">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-white truncate">Menunggu</dt>
                                    <dd class="text-lg font-medium text-white">{{ $pemesananPending }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Diterima -->
                <div class="bg-gradient-to-r from-green-500 to-green-600 overflow-hidden shadow rounded-lg transform hover:scale-105 transition duration-300">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-white truncate">Diterima</dt>
                                    <dd class="text-lg font-medium text-white">{{ $pemesananDiterima }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pendapatan Bulan Ini -->
                <div class="bg-gradient-to-r from-purple-500 to-purple-600 overflow-hidden shadow rounded-lg transform hover:scale-105 transition duration-300">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-white truncate">Bulan Ini</dt>
                                    <dd class="text-lg font-medium text-white">Rp {{ number_format($pendapatanBulanIni, 0, ',', '.') }}</dd>
                                    @if(isset($growthBulanan))
                                    <div class="text-xs text-white opacity-80">
                                        @if($growthBulanan > 0)
                                            ↗️ +{{ number_format($growthBulanan, 1) }}%
                                        @else
                                            ➡️ {{ number_format($growthBulanan, 1) }}%
                                        @endif
                                    </div>
                                    @endif
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- NEW ANALYTICS SECTION - BISA TOGGLE -->
            <div id="analyticsSection" style="display: none;">
                
                <!-- Advanced Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <!-- Today -->
                    <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 text-white p-6 rounded-lg shadow-lg">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-indigo-200 text-sm">Hari Ini</p>
                                <p class="text-2xl font-bold">Rp {{ number_format($pendapatanHariIni ?? 0, 0, ',', '.') }}</p>
                            </div>
                            <div class="text-3xl">💰</div>
                        </div>
                    </div>

                    <!-- This Week -->
                    <div class="bg-gradient-to-br from-teal-500 to-teal-600 text-white p-6 rounded-lg shadow-lg">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-teal-200 text-sm">Minggu Ini</p>
                                <p class="text-2xl font-bold">Rp {{ number_format($pendapatanMingguIni ?? 0, 0, ',', '.') }}</p>
                            </div>
                            <div class="text-3xl">📅</div>
                        </div>
                    </div>

                    <!-- This Year -->
                    <div class="bg-gradient-to-br from-pink-500 to-pink-600 text-white p-6 rounded-lg shadow-lg">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-pink-200 text-sm">Tahun Ini</p>
                                <p class="text-2xl font-bold">Rp {{ number_format($pendapatanTahunIni ?? 0, 0, ',', '.') }}</p>
                            </div>
                            <div class="text-3xl">🏆</div>
                        </div>
                    </div>
                </div>

                <!-- Charts Section -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                    <!-- Monthly Chart -->
                    <div class="bg-white rounded-lg shadow-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">📈 Tren Pendapatan 12 Bulan</h3>
                        <div class="h-64">
                            <canvas id="monthlyChart"></canvas>
                        </div>
                    </div>

                    <!-- Property Performance -->
                    <div class="bg-white rounded-lg shadow-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">🏠 Top Properti</h3>
                        <div class="h-64">
                            <canvas id="propertyChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Target Progress -->
                @if(isset($targetBulanan))
                <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">🎯 Progress Target Bulanan</h3>
                        <span class="text-2xl font-bold text-indigo-600">{{ number_format($persentaseTarget, 1) }}%</span>
                    </div>
                    
                    <div class="w-full bg-gray-200 rounded-full h-4 mb-4">
                        <div class="bg-gradient-to-r from-indigo-500 to-purple-600 h-4 rounded-full transition-all duration-1000" 
                             style="width: {{ min($persentaseTarget, 100) }}%"></div>
                    </div>
                    
                    <div class="grid grid-cols-3 gap-4 text-center text-sm">
                        <div>
                            <span class="text-gray-600">Target</span>
                            <p class="font-semibold">Rp {{ number_format($targetBulanan, 0, ',', '.') }}</p>
                        </div>
                        <div>
                            <span class="text-gray-600">Tercapai</span>
                            <p class="font-semibold">Rp {{ number_format($pendapatanBulanIni, 0, ',', '.') }}</p>
                        </div>
                        <div>
                            <span class="text-gray-600">Sisa</span>
                            <p class="font-semibold">Rp {{ number_format(max(0, $targetBulanan - $pendapatanBulanIni), 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- EXISTING Table Pemesanan - TETAP SAMA -->
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Daftar Pemesanan Terbaru</h3>
                </div>
                <div class="overflow-x-auto">
                    @if($pemesanan->count() > 0)
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pemesan</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Properti</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Periode</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($pemesanan as $item)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                                    <span class="text-sm font-medium text-gray-700">
                                                        {{ strtoupper(substr($item->penyewa->username ?? 'U', 0, 1)) }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $item->penyewa->username ?? 'User' }}</div>
                                                <div class="text-sm text-gray-500">{{ $item->penyewa->email ?? '' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $item->properti->nama }}</div>
                                        <div class="text-sm text-gray-500">{{ Str::limit($item->properti->alamat, 30) }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $item->tanggal_mulai->format('d/m/Y') }}</div>
                                        <div class="text-sm text-gray-500">s/d {{ $item->tanggal_selesai->format('d/m/Y') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-green-600">Rp {{ number_format($item->total_harga, 0, ',', '.') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ 
                                            $item->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                            ($item->status === 'diterima' ? 'bg-green-100 text-green-800' : 
                                            ($item->status === 'ditolak' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800')) 
                                        }}">
                                            {{ ucfirst($item->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $item->created_at->diffForHumans() }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="px-6 py-4">
                            {{ $pemesanan->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada pemesanan</h3>
                            <p class="mt-1 text-sm text-gray-500">Pemesanan akan muncul di sini setelah ada penyewa yang memesan properti Anda.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        let analyticsVisible = false;
        let monthlyChart, propertyChart;

        function toggleView() {
            const section = document.getElementById('analyticsSection');
            const button = event.target;
            
            if (analyticsVisible) {
                section.style.display = 'none';
                button.textContent = '📊 Show Analytics';
                analyticsVisible = false;
            } else {
                section.style.display = 'block';
                button.textContent = '📋 Hide Analytics';
                analyticsVisible = true;
                
                // Initialize charts when shown
                setTimeout(() => {
                    initCharts();
                }, 100);
            }
        }

        function initCharts() {
            if (monthlyChart) monthlyChart.destroy();
            if (propertyChart) propertyChart.destroy();

            // Monthly Chart
            const monthlyData = @json($monthlyData ?? []);
            const ctx1 = document.getElementById('monthlyChart');
            if (ctx1) {
                monthlyChart = new Chart(ctx1, {
                    type: 'line',
                    data: {
                        labels: monthlyData.map(item => item.month),
                        datasets: [{
                            label: 'Pendapatan',
                            data: monthlyData.map(item => item.revenue),
                            borderColor: 'rgb(79, 70, 229)',
                            backgroundColor: 'rgba(79, 70, 229, 0.1)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                                    }
                                }
                            }
                        }
                    }
                });
            }

            // Property Chart
            const propertyData = @json($propertyPerformance ?? []);
            const ctx2 = document.getElementById('propertyChart');
            if (ctx2 && propertyData.length > 0) {
                const colors = ['#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6'];
                propertyChart = new Chart(ctx2, {
                    type: 'doughnut',
                    data: {
                        labels: propertyData.map(item => item.name),
                        datasets: [{
                            data: propertyData.map(item => item.revenue),
                            backgroundColor: colors.slice(0, propertyData.length),
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom'
                            }
                        }
                    }
                });
            }
        }
    </script>
</x-app-layout>