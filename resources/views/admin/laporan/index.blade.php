<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Laporan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Statistics -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <p class="text-gray-500 text-sm">Total Pemesanan</p>
                        <p class="text-3xl font-bold text-blue-600">{{ $totalPemesanan }}</p>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <p class="text-gray-500 text-sm">Total Lama Sewa</p>
                        <p class="text-3xl font-bold text-green-600">{{ $totalRevenue }} hari</p>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <p class="text-gray-500 text-sm">Pemesanan Bulan Ini</p>
                        <p class="text-3xl font-bold text-orange-600">{{ $pemesananBulanIni }}</p>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <p class="text-gray-500 text-sm">Rating Rata-rata</p>
                        <p class="text-3xl font-bold text-yellow-600">{{ round($ratingRataRata, 2) ?? 0 }} / 5</p>
                    </div>
                </div>
            </div>

            <!-- Charts -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h3 class="text-lg font-semibold mb-4">Pemesanan Per Bulan</h3>
                        <canvas id="pemesananChart"></canvas>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h3 class="text-lg font-semibold mb-4">Revenue Per Bulan</h3>
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Top Properties -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h3 class="text-lg font-semibold mb-4">Properti Terpopuler</h3>
                        <div class="space-y-4">
                            @foreach($propertiTerpopuler as $prop)
                                <div class="flex justify-between items-center pb-2 border-b">
                                    <span>{{ $prop->nama }}</span>
                                    <span class="font-bold text-blue-600">{{ $prop->pemesanan_count }} pemesanan</span>
                                </div>
                            @endforeach
                        </div>
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
