<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard Admin') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Total Users -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 dark:text-gray-400 text-sm font-medium">Total Pengguna</p>
                                <p class="text-3xl font-bold text-blue-600">{{ $totalUsers }}</p>
                            </div>
                            <div class="text-blue-600 text-4xl">
                                <i class="fas fa-users"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Properti -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 dark:text-gray-400 text-sm font-medium">Total Properti</p>
                                <p class="text-3xl font-bold text-green-600">{{ $totalProperti }}</p>
                            </div>
                            <div class="text-green-600 text-4xl">
                                <i class="fas fa-home"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Pemesanan -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 dark:text-gray-400 text-sm font-medium">Total Pemesanan</p>
                                <p class="text-3xl font-bold text-orange-600">{{ $totalPemesanan }}</p>
                            </div>
                            <div class="text-orange-600 text-4xl">
                                <i class="fas fa-calendar"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Review -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 dark:text-gray-400 text-sm font-medium">Total Review</p>
                                <p class="text-3xl font-bold text-yellow-600">{{ $totalReview }}</p>
                            </div>
                            <div class="text-yellow-600 text-4xl">
                                <i class="fas fa-star"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-8">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h2 class="text-2xl font-bold mb-4">Akses Cepat</h2>
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                        <a href="{{ route('admin.users.index') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded text-center transition">
                            <i class="fas fa-users mb-2 block"></i>
                            <span class="text-sm">Kelola Pengguna</span>
                        </a>
                        <a href="{{ route('admin.properti.index') }}" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded text-center transition">
                            <i class="fas fa-home mb-2 block"></i>
                            <span class="text-sm">Kelola Properti</span>
                        </a>
                        <a href="{{ route('admin.pemesanan.index') }}" class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded text-center transition">
                            <i class="fas fa-calendar mb-2 block"></i>
                            <span class="text-sm">Kelola Pemesanan</span>
                        </a>
                        <a href="{{ route('admin.reviews.index') }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded text-center transition">
                            <i class="fas fa-star mb-2 block"></i>
                            <span class="text-sm">Kelola Review</span>
                        </a>
                        <a href="{{ route('admin.laporan') }}" class="bg-purple-500 hover:bg-purple-600 text-white px-4 py-2 rounded text-center transition">
                            <i class="fas fa-chart-bar mb-2 block"></i>
                            <span class="text-sm">Laporan</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Pemesanan Per Bulan -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h3 class="text-lg font-semibold mb-4">Pemesanan Per Bulan</h3>
                        <canvas id="pemesananChart"></canvas>
                    </div>
                </div>

                <!-- Revenue Per Bulan -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h3 class="text-lg font-semibold mb-4">Revenue Per Bulan</h3>
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Pemesanan Chart
        const pemesananCtx = document.getElementById('pemesananChart').getContext('2d');
        new Chart(pemesananCtx, {
            type: 'line',
            data: {
                labels: [
                    @foreach($pemesananPerBulan as $data)
                        'Bulan {{ $data->bulan }}',
                    @endforeach
                ],
                datasets: [{
                    label: 'Pemesanan',
                    data: [
                        @foreach($pemesananPerBulan as $data)
                            {{ $data->total }},
                        @endforeach
                    ],
                    borderColor: 'rgb(75, 192, 192)',
                    backgroundColor: 'rgba(75, 192, 192, 0.1)',
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Revenue Chart
        const revenueCtx = document.getElementById('revenueChart').getContext('2d');
        new Chart(revenueCtx, {
            type: 'bar',
            data: {
                labels: [
                    @foreach($revenuePerBulan as $data)
                        'Bulan {{ $data->bulan }}',
                    @endforeach
                ],
                datasets: [{
                    label: 'Revenue (Rp)',
                    data: [
                        @foreach($revenuePerBulan as $data)
                            {{ $data->total ?? 0 }},
                        @endforeach
                    ],
                    backgroundColor: 'rgba(54, 162, 235, 0.5)',
                    borderColor: 'rgb(54, 162, 235)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</x-app-layout>
