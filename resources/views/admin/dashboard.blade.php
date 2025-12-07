<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Dashboard Admin') }}
                </h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Ringkasan aktivitas sistem, status pemesanan, dan performa properti dalam satu layar.
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- STAT CARDS --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                {{-- Total Users --}}
                <div
                    class="relative overflow-hidden rounded-2xl  bg-slate-800 border border-slate-100/80 dark:border-slate-800 shadow-lg shadow-slate-200/60 dark:shadow-black/30 hover:-translate-y-1 hover:shadow-xl transition-all duration-300">
                    <div class="absolute -right-6 -top-6 w-24 h-24 bg-blue-100/50 dark:bg-blue-500/10 rounded-full"></div>
                    <div class="p-5 relative z-10 space-y-3">
                        <div class="flex items-center justify-between">
                            <div class="space-y-1">
                                <p class="text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                    Total Pengguna
                                </p>
                                <p class="text-3xl font-bold text-blue-600 dark:text-blue-400">
                                    {{ $totalUsers }}
                                </p>
                            </div>
                            <div
                                class="flex h-12 w-12 items-center justify-center rounded-full bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400">
                                <i class="fas fa-users text-2xl"></i>
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            Jumlah seluruh akun yang terdaftar di sistem.
                        </p>
                    </div>
                </div>

                {{-- Total Properti --}}
                <div
                    class="relative overflow-hidden rounded-2xl  bg-slate-800 border border-slate-100/80 dark:border-slate-800 shadow-lg shadow-slate-200/60 dark:shadow-black/30 hover:-translate-y-1 hover:shadow-xl transition-all duration-300">
                    <div class="absolute -right-6 -top-6 w-24 h-24 bg-emerald-100/50 dark:bg-emerald-500/10 rounded-full"></div>
                    <div class="p-5 relative z-10 space-y-3">
                        <div class="flex items-center justify-between">
                            <div class="space-y-1">
                                <p class="text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                    Total Properti
                                </p>
                                <p class="text-3xl font-bold text-emerald-600 dark:text-emerald-400">
                                    {{ $totalProperti }}
                                </p>
                            </div>
                            <div
                                class="flex h-12 w-12 items-center justify-center rounded-full bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400">
                                <i class="fas fa-home text-2xl"></i>
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            Jumlah properti yang aktif dan siap disewa.
                        </p>
                    </div>
                </div>

                {{-- Total Pemesanan --}}
                <div
                    class="relative overflow-hidden rounded-2xl  bg-slate-800 border border-slate-100/80 dark:border-slate-800 shadow-lg shadow-slate-200/60 dark:shadow-black/30 hover:-translate-y-1 hover:shadow-xl transition-all duration-300">
                    <div class="absolute -right-6 -top-6 w-24 h-24 bg-orange-100/50 dark:bg-orange-500/10 rounded-full"></div>
                    <div class="p-5 relative z-10 space-y-3">
                        <div class="flex items-center justify-between">
                            <div class="space-y-1">
                                <p class="text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                    Total Pemesanan
                                </p>
                                <p class="text-3xl font-bold text-orange-500 dark:text-orange-400">
                                    {{ $totalPemesanan }}
                                </p>
                            </div>
                            <div
                                class="flex h-12 w-12 items-center justify-center rounded-full bg-orange-50 dark:bg-orange-500/10 text-orange-500 dark:text-orange-400">
                                <i class="fas fa-calendar text-2xl"></i>
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            Akumulasi pemesanan yang tercatat di sistem.
                        </p>
                    </div>
                </div>

                {{-- Total Review --}}
                <div
                    class="relative overflow-hidden rounded-2xl  bg-slate-800 border border-slate-100/80 dark:border-slate-800 shadow-lg shadow-slate-200/60 dark:shadow-black/30 hover:-translate-y-1 hover:shadow-xl transition-all duration-300">
                    <div class="absolute -right-6 -top-6 w-24 h-24 bg-amber-100/50 dark:bg-amber-500/10 rounded-full"></div>
                    <div class="p-5 relative z-10 space-y-3">
                        <div class="flex items-center justify-between">
                            <div class="space-y-1">
                                <p class="text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                    Total Review
                                </p>
                                <p class="text-3xl font-bold text-amber-500 dark:text-amber-400">
                                    {{ $totalReview }}
                                </p>
                            </div>
                            <div
                                class="flex h-12 w-12 items-center justify-center rounded-full bg-amber-50 dark:bg-amber-500/10 text-amber-500 dark:text-amber-400">
                                <i class="fas fa-star text-2xl"></i>
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            Feedback pengguna terhadap properti yang telah disewa.
                        </p>
                    </div>
                </div>
            </div>

            {{-- CHARTS ROW 1 --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                {{-- Status Pemesanan --}}
                <div
                    class="rounded-2xl  bg-slate-800 border border-slate-100/80 dark:border-slate-800 shadow-lg shadow-slate-200/60 dark:shadow-black/30">
                    <div class="p-5 flex flex-col h-[330px]">
                        <div class="flex items-center justify-between mb-2">
                            <div>
                                <h3 class="text-base font-semibold text-gray-800 dark:text-gray-100">
                                    Status Pemesanan
                                </h3>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    Proporsi pemesanan berdasarkan status terakhir.
                                </p>
                            </div>
                        </div>
                        <div class="mt-4 flex-1 min-h-0">
                            <canvas id="statusPemesananChart" class="w-full h-full"></canvas>
                        </div>
                    </div>
                </div>

                {{-- Status Pembayaran --}}
                <div
                    class="rounded-2xl  bg-slate-800 border border-slate-100/80 dark:border-slate-800 shadow-lg shadow-slate-200/60 dark:shadow-black/30">
                    <div class="p-5 flex flex-col h-[330px]">
                        <div class="flex items-center justify-between mb-2">
                            <div>
                                <h3 class="text-base font-semibold text-gray-800 dark:text-gray-100">
                                    Status Pembayaran
                                </h3>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    Perbandingan pesanan yang sudah dan belum dibayar.
                                </p>
                            </div>
                        </div>
                        <div class="mt-4 flex-1 min-h-0">
                            <canvas id="statusPembayaranChart" class="w-full h-full"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            {{-- CHARTS ROW 2 --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                {{-- Properti Terpopuler --}}
                <div
                    class="rounded-2xl  bg-slate-800 border border-slate-100/80 dark:border-slate-800 shadow-lg shadow-slate-200/60 dark:shadow-black/30">
                    <div class="p-5 flex flex-col h-[340px]">
                        <div class="flex items-center justify-between mb-2">
                            <div>
                                <h3 class="text-base font-semibold text-gray-800 dark:text-gray-100">
                                    Top 5 Properti Terpopuler
                                </h3>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    Berdasarkan jumlah pemesanan terbanyak.
                                </p>
                            </div>
                        </div>
                        <div class="mt-4 flex-1 min-h-0">
                            <canvas id="propertiTerpopulerChart" class="w-full h-full"></canvas>
                        </div>
                    </div>
                </div>

                {{-- Review Per Bulan --}}
                <div
                    class="rounded-2xl  bg-slate-800 border border-slate-100/80 dark:border-slate-800 shadow-lg shadow-slate-200/60 dark:shadow-black/30">
                    <div class="p-5 flex flex-col h-[340px]">
                        <div class="flex items-center justify-between mb-2">
                            <div>
                                <h3 class="text-base font-semibold text-gray-800 dark:text-gray-100">
                                    Review Per Bulan
                                </h3>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    Tren jumlah review yang masuk setiap bulan.
                                </p>
                            </div>
                        </div>
                        <div class="mt-4 flex-1 min-h-0">
                            <canvas id="reviewChart" class="w-full h-full"></canvas>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- CHART.JS --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Global style biar chart kelihatan lebih rapi & nyatu dengan Tailwind
        const isDark = window.matchMedia &&
            window.matchMedia('(prefers-color-scheme: dark)').matches;

        Chart.defaults.font.family =
            '"Inter", system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif';
        Chart.defaults.color = isDark ? '#e5e7eb' : '#4b5563';
        Chart.defaults.plugins.legend.labels.usePointStyle = true;
        Chart.defaults.plugins.legend.labels.pointStyle = 'circle';
        Chart.defaults.plugins.legend.labels.boxWidth = 8;

        // 1. Status Pemesanan Chart (Pie)
        const statusPemesananCtx = document.getElementById('statusPemesananChart').getContext('2d');
        new Chart(statusPemesananCtx, {
            type: 'pie',
            data: {
                labels: ['Pending', 'Confirmed', 'Cancelled'],
                datasets: [{
                    data: [
                        {{ $statusPemesanan['pending'] }},
                        {{ $statusPemesanan['confirmed'] }},
                        {{ $statusPemesanan['cancelled'] }}
                    ],
                    backgroundColor: [
                        'rgba(249, 115, 22, 0.85)',   // Orange
                        'rgba(34, 197, 94, 0.85)',    // Green
                        'rgba(239, 68, 68, 0.85)'     // Red
                    ],
                    borderColor: [
                        'rgb(248, 171, 112)',
                        'rgb(74, 222, 128)',
                        'rgb(252, 165, 165)'
                    ],
                    borderWidth: 1.5,
                    hoverOffset: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                layout: {
                    padding: 10
                },
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 14
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                let value = context.parsed || 0;
                                let total = context.dataset.data.reduce((a, b) => a + b, 0);
                                let percentage = total ? ((value / total) * 100).toFixed(1) : 0;
                                return label + ': ' + value + ' (' + percentage + '%)';
                            }
                        }
                    }
                }
            }
        });

        // 2. Status Pembayaran Chart (Doughnut)
        const statusPembayaranCtx = document.getElementById('statusPembayaranChart').getContext('2d');
        new Chart(statusPembayaranCtx, {
            type: 'doughnut',
            data: {
                labels: ['Belum Bayar', 'Sudah Bayar'],
                datasets: [{
                    data: [
                        {{ $statusPembayaran['belum_bayar'] }},
                        {{ $statusPembayaran['sudah_bayar'] }}
                    ],
                    backgroundColor: [
                        'rgba(239, 68, 68, 0.85)',   // Red
                        'rgba(34, 197, 94, 0.85)'    // Green
                    ],
                    borderColor: [
                        'rgb(252, 165, 165)',
                        'rgb(74, 222, 128)'
                    ],
                    borderWidth: 1.5,
                    hoverOffset: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '60%',
                layout: {
                    padding: 10
                },
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 14
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                let value = context.parsed || 0;
                                let total = context.dataset.data.reduce((a, b) => a + b, 0);
                                let percentage = total ? ((value / total) * 100).toFixed(1) : 0;
                                return label + ': ' + value + ' (' + percentage + '%)';
                            }
                        }
                    }
                }
            }
        });

        // 3. Properti Terpopuler Chart (Horizontal Bar)
        const propertiTerpopulerCtx = document.getElementById('propertiTerpopulerChart').getContext('2d');
        new Chart(propertiTerpopulerCtx, {
            type: 'bar',
            data: {
                labels: [
                    @foreach($propertiTerpopuler as $data)
                        '{{ $data->properti->nama ?? "N/A" }}',
                    @endforeach
                ],
                datasets: [{
                    label: 'Jumlah Pemesanan',
                    data: [
                        @foreach($propertiTerpopuler as $data)
                            {{ $data->total_pemesanan }},
                        @endforeach
                    ],
                    backgroundColor: 'rgba(34, 197, 94, 0.70)',
                    borderColor: 'rgb(34, 197, 94)',
                    borderWidth: 1.8,
                    borderRadius: 8,
                    barThickness: 18,
                    maxBarThickness: 22
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                indexAxis: 'y',
                layout: {
                    padding: {
                        left: 8,
                        right: 12,
                        top: 5,
                        bottom: 5
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Total Pemesanan: ' + context.parsed.x;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        grid: {
                            drawBorder: false,
                            color: isDark ? 'rgba(75, 85, 99, 0.5)' : 'rgba(209, 213, 219, 0.7)'
                        },
                        ticks: {
                            stepSize: 1,
                            precision: 0
                        }
                    },
                    y: {
                        grid: {
                            display: false,
                            drawBorder: false
                        }
                    }
                }
            }
        });

        // 4. Review Per Bulan Chart (Line)
        const reviewCtx = document.getElementById('reviewChart').getContext('2d');
        new Chart(reviewCtx, {
            type: 'line',
            data: {
                labels: [
                    @foreach($reviewPerBulan as $data)
                        '{{ $data['bulan'] }}',
                    @endforeach
                ],
                datasets: [{
                    label: 'Jumlah Review',
                    data: [
                        @foreach($reviewPerBulan as $data)
                            {{ $data['total'] }},
                        @endforeach
                    ],
                    borderColor: 'rgb(234, 179, 8)',
                    backgroundColor: 'rgba(234, 179, 8, 0.12)',
                    tension: 0.4,
                    fill: true,
                    pointRadius: 4,
                    pointHoverRadius: 5,
                    pointHitRadius: 8,
                    pointBackgroundColor: 'rgb(234, 179, 8)',
                    pointBorderWidth: 1.5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                layout: {
                    padding: {
                        left: 6,
                        right: 12,
                        top: 5,
                        bottom: 5
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            padding: 10
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': ' + context.parsed.y + ' review';
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            precision: 0
                        },
                        grid: {
                            drawBorder: false,
                            color: isDark ? 'rgba(75, 85, 99, 0.5)' : 'rgba(209, 213, 219, 0.7)'
                        }
                    }
                }
            }
        });
    </script>
</x-app-layout>
