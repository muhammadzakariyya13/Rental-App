<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Welcome Message -->
            <div class="bg-gradient-to-r from-blue-500 to-purple-600 overflow-hidden shadow-xl sm:rounded-lg mb-6">
                <div class="p-6 text-white">
                    <h3 class="text-2xl font-bold mb-2">👋 Selamat datang, pemilik!</h3>
                    <p class="text-blue-100">Kelola properti dan analytics pendapatan Anda dengan mudah.</p>
                </div>
            </div>

            <!-- ENHANCED Stats Cards with Real-time Data -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <!-- Pendapatan Hari Ini -->
                <div class="bg-gradient-to-r from-green-500 to-green-600 overflow-hidden shadow-xl sm:rounded-lg transform hover:scale-105 transition duration-300">
                    <div class="p-6 text-white">
                        <div class="flex items-center">
                            <div class="flex-1">
                                <h3 class="text-2xl font-bold">Rp {{ number_format($pendapatanHariIni, 0, ',', '.') }}</h3>
                                <p class="text-green-100">Hari Ini</p>
                                <span class="text-green-200 text-xs">💰 Real-time</span>
                            </div>
                            <div class="text-green-200">
                                <i class="fas fa-coins text-3xl"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pendapatan Bulan Ini -->
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 overflow-hidden shadow-xl sm:rounded-lg transform hover:scale-105 transition duration-300">
                    <div class="p-6 text-white">
                        <div class="flex items-center">
                            <div class="flex-1">
                                <h3 class="text-xl font-bold">Rp {{ number_format($pendapatanBulanIni/1000000, 1) }}M</h3>
                                <p class="text-blue-100">Bulan Ini</p>
                                <a href="{{ route('pemilik.pemesanan') }}" class="text-blue-200 hover:text-white text-sm">
                                    Lihat Detail →
                                </a>
                            </div>
                            <div class="text-blue-200">
                                <i class="fas fa-chart-line text-3xl"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Properties -->
                <div class="bg-gradient-to-r from-purple-500 to-purple-600 overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6 text-white">
                        <div class="flex items-center">
                            <div class="flex-1">
                                <h3 class="text-3xl font-bold">{{ $jumlahProperti }}</h3>
                                <p class="text-purple-100">Total Properti</p>
                                <span class="text-purple-200 text-sm">{{ $propertiDisewa }} Disewa</span>
                            </div>
                            <div class="text-purple-200">
                                <i class="fas fa-home text-3xl"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pendapatan Tahun Ini -->
                <div class="bg-gradient-to-r from-orange-500 to-orange-600 overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6 text-white">
                        <div class="flex items-center">
                            <div class="flex-1">
                                <h3 class="text-lg font-bold">Rp {{ number_format($pendapatanTahunIni/1000000, 1) }}M</h3>
                                <p class="text-orange-100">Tahun {{ now()->year }}</p>
                                <span class="text-orange-200 text-sm">🏆 Total Annual</span>
                            </div>
                            <div class="text-orange-200">
                                <i class="fas fa-trophy text-3xl"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CHARTS SECTION -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <!-- Revenue Chart -->
                <div class="bg-white rounded-lg shadow-xl p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">📈 Tren Pendapatan 6 Bulan</h3>
                    <div class="h-64">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>

                <!-- Top Properties Chart -->
                <div class="bg-white rounded-lg shadow-xl p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">🏠 Properti Terpopuler</h3>
                    <div class="h-64">
                        <canvas id="propertyChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- PROPERTI PERFORMANCE TABLE -->
            <div class="bg-white rounded-lg shadow-xl p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">🏆 Top Performing Properties</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Properti</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Booking</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Harga</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($propertiTerpopuler as $properti)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $properti->nama }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                        {{ $properti->total_bookings }} bookings
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ 
                                        $properti->status == 'tersedia' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' 
                                    }}">
                                        {{ ucfirst($properti->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    Rp {{ number_format($properti->harga, 0, ',', '.') }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- QUICK ACTIONS -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">⚡ Quick Actions</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <a href="{{ route('pemilik.properti') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-4 rounded-lg text-center font-medium transition duration-300">
                            🏠 Kelola Properti
                        </a>
                        <a href="{{ route('pemilik.pemesanan') }}" class="bg-green-500 hover:bg-green-600 text-white px-6 py-4 rounded-lg text-center font-medium transition duration-300">
                            📊 Analisa Pendapatan
                        </a>
                        <a href="{{ route('pemilik.reviews.index') }}" class="bg-orange-500 hover:bg-orange-600 text-white px-6 py-4 rounded-lg text-center font-medium transition duration-300">
                            ⭐ Kelola Review ({{ $reviewTerbaru }})
                        </a>
                        <a href="{{ route('pemilik.bookings.index') }}" class="bg-purple-500 hover:bg-purple-600 text-white px-6 py-4 rounded-lg text-center font-medium transition duration-300">
                            📅 Manage Bookings
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- CHART.JS SCRIPTS -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Revenue Chart
        const revenueData = @json($chartData);
        const ctx1 = document.getElementById('revenueChart').getContext('2d');
        
        new Chart(ctx1, {
            type: 'line',
            data: {
                labels: revenueData.map(item => item.month),
                datasets: [{
                    label: 'Pendapatan',
                    data: revenueData.map(item => item.revenue),
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
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
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });

        // Property Performance Chart
        const propertyData = @json($propertiTerpopuler);
        const ctx2 = document.getElementById('propertyChart').getContext('2d');
        
        new Chart(ctx2, {
            type: 'doughnut',
            data: {
                labels: propertyData.map(item => item.nama.substring(0, 20) + '...'),
                datasets: [{
                    data: propertyData.map(item => item.total_bookings),
                    backgroundColor: [
                        '#3B82F6',
                        '#10B981', 
                        '#F59E0B',
                        '#EF4444',
                        '#8B5CF6'
                    ]
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
    </script>
</x-app-layout>