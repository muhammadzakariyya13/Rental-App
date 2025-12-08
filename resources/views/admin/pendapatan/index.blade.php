<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Pendapatan Admin') }}
                </h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Ringkasan biaya admin (0.5%) yang dikumpulkan dari setiap transaksi pemesanan yang berhasil.
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- STAT CARDS --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                {{-- Total Pendapatan Admin --}}
                <div
                    class="relative overflow-hidden rounded-2xl bg-slate-800 border border-slate-100/80 dark:border-slate-800 shadow-lg shadow-slate-200/60 dark:shadow-black/30 hover:-translate-y-1 hover:shadow-xl transition-all duration-300">
                    <div class="absolute -right-6 -top-6 w-24 h-24 bg-emerald-100/50 dark:bg-emerald-500/10 rounded-full"></div>
                    <div class="p-5 relative z-10 space-y-3">
                        <div class="flex items-center justify-between">
                            <div class="space-y-1">
                                <p class="text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                    Total Pendapatan Admin
                                </p>
                                <p class="text-3xl font-bold text-emerald-600 dark:text-emerald-400">
                                    Rp {{ number_format($totalPendapatanAdmin, 0, ',', '.') }}
                                </p>
                            </div>
                            <div
                                class="flex h-12 w-12 items-center justify-center rounded-full bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400">
                                <i class="fas fa-money-bill-wave text-2xl"></i>
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            Biaya admin dari seluruh transaksi.
                        </p>
                    </div>
                </div>

                {{-- Pendapatan Bulan Ini --}}
                <div
                    class="relative overflow-hidden rounded-2xl bg-slate-800 border border-slate-100/80 dark:border-slate-800 shadow-lg shadow-slate-200/60 dark:shadow-black/30 hover:-translate-y-1 hover:shadow-xl transition-all duration-300">
                    <div class="absolute -right-6 -top-6 w-24 h-24 bg-blue-100/50 dark:bg-blue-500/10 rounded-full"></div>
                    <div class="p-5 relative z-10 space-y-3">
                        <div class="flex items-center justify-between">
                            <div class="space-y-1">
                                <p class="text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                    Pendapatan Bulan Ini
                                </p>
                                <p class="text-3xl font-bold text-blue-600 dark:text-blue-400">
                                    Rp {{ number_format($pendapatanBulanIni, 0, ',', '.') }}
                                </p>
                            </div>
                            <div
                                class="flex h-12 w-12 items-center justify-center rounded-full bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400">
                                <i class="fas fa-calendar-alt text-2xl"></i>
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            Biaya admin bulan {{ now()->format('F Y') }}.
                        </p>
                    </div>
                </div>

                {{-- Pendapatan Tahun Ini --}}
                <div
                    class="relative overflow-hidden rounded-2xl bg-slate-800 border border-slate-100/80 dark:border-slate-800 shadow-lg shadow-slate-200/60 dark:shadow-black/30 hover:-translate-y-1 hover:shadow-xl transition-all duration-300">
                    <div class="absolute -right-6 -top-6 w-24 h-24 bg-orange-100/50 dark:bg-orange-500/10 rounded-full"></div>
                    <div class="p-5 relative z-10 space-y-3">
                        <div class="flex items-center justify-between">
                            <div class="space-y-1">
                                <p class="text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                    Pendapatan Tahun Ini
                                </p>
                                <p class="text-3xl font-bold text-orange-500 dark:text-orange-400">
                                    Rp {{ number_format($pendapatanTahunIni, 0, ',', '.') }}
                                </p>
                            </div>
                            <div
                                class="flex h-12 w-12 items-center justify-center rounded-full bg-orange-50 dark:bg-orange-500/10 text-orange-500 dark:text-orange-400">
                                <i class="fas fa-chart-line text-2xl"></i>
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            Biaya admin tahun {{ now()->year }}.
                        </p>
                    </div>
                </div>
            </div>

            {{-- CHART --}}
            <div
                class="bg-white dark:bg-slate-800 border border-slate-100/80 dark:border-slate-800 shadow-lg shadow-slate-200/60 dark:shadow-black/30 overflow-hidden sm:rounded-2xl">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 flex items-center gap-2">
                        <i class="fas fa-chart-bar text-purple-500"></i>
                        Grafik Pendapatan Admin (12 Bulan Terakhir)
                    </h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Visualisasi perolehan biaya admin setiap bulan.
                    </p>
                </div>
                <div class="p-6">
                    <canvas id="pendapatanChart" height="80"></canvas>
                </div>
            </div>

            {{-- TABEL TRANSAKSI --}}
            <div
                class="bg-white dark:bg-slate-800 border border-slate-100/80 dark:border-slate-800 shadow-lg shadow-slate-200/60 dark:shadow-black/30 overflow-hidden sm:rounded-2xl">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 flex items-center gap-2">
                        <i class="fas fa-list-alt text-indigo-500"></i>
                        Daftar Transaksi dengan Biaya Admin
                    </h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Riwayat transaksi yang berhasil dengan rincian biaya admin.
                    </p>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-900">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    ID
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Properti
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Penyewa
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Pemilik
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Total Harga
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-orange-500 dark:text-orange-400 uppercase tracking-wider">
                                    Biaya Admin (0.5%)
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Tanggal Bayar
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-slate-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($transaksi as $item)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-900 transition">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                        #{{ $item->id_pemesanan }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            @if($item->properti && $item->properti->gambar)
                                                <img src="{{ asset($item->properti->gambar) }}" 
                                                     alt="{{ $item->properti->nama }}" 
                                                     class="w-16 h-16 rounded-lg object-cover shadow-sm flex-shrink-0">
                                            @else
                                                <div class="w-16 h-16 bg-gray-200 dark:bg-gray-700 rounded-lg flex items-center justify-center flex-shrink-0">
                                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                                    </svg>
                                                </div>
                                            @endif
                                            <div>
                                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                    {{ $item->properti->nama ?? '-' }}
                                                </div>
                                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                                    {{ Str::limit($item->properti->alamat ?? '', 40) }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                        {{ $item->akun->nama ?? $item->akun->username ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                        {{ $item->properti->pemilik->nama ?? $item->properti->pemilik->username ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-emerald-600 dark:text-emerald-400">
                                        Rp {{ number_format($item->total_harga, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-orange-500 dark:text-orange-400">
                                        Rp {{ number_format($item->biaya_admin, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ $item->paid_at ? $item->paid_at->format('d M Y H:i') : '-' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-8 text-center text-sm text-gray-500 dark:text-gray-400">
                                        <i class="fas fa-inbox text-4xl mb-2 text-gray-300 dark:text-gray-600"></i>
                                        <p>Belum ada transaksi dengan biaya admin.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-4 border-t border-gray-200 dark:border-gray-700">
                    {{ $transaksi->links() }}
                </div>
            </div>

        </div>
    </div>

    {{-- CHART.JS SCRIPT --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('pendapatanChart').getContext('2d');
        const chartData = @json($chartData);

        const labels = chartData.map(item => item.bulan);
        const data = chartData.map(item => item.pendapatan);

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Pendapatan Admin (Rp)',
                    data: data,
                    backgroundColor: 'rgba(249, 115, 22, 0.6)',
                    borderColor: 'rgba(249, 115, 22, 1)',
                    borderWidth: 2,
                    borderRadius: 6,
                    hoverBackgroundColor: 'rgba(249, 115, 22, 0.8)',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            color: '#9CA3AF'
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            color: '#9CA3AF',
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        },
                        grid: {
                            color: 'rgba(156, 163, 175, 0.1)'
                        }
                    },
                    x: {
                        ticks: {
                            color: '#9CA3AF'
                        },
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    </script>
</x-app-layout>
