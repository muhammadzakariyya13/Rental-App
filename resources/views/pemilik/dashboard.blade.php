{{-- filepath: D:\PROJEK LARAVEL\Rental-App\resources\views\pemilik\dashboard.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            🏠 Dashboard Pemilik
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Welcome Banner -->
            <div class="bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg p-6 text-white mb-6 shadow-lg">
                <div>
                    <h1 class="text-2xl font-bold mb-2">👋 Selamat datang, {{ Auth::user()->name ?? Auth::user()->username }}!</h1>
                    <p class="text-blue-100">Kelola properti dan pemesanan Anda dengan mudah dari dashboard ini.</p>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
                <!-- Total Properties -->
                <div class="bg-white border border-gray-200 rounded-lg p-4 shadow hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-2xl font-bold text-blue-600">{{ $totalProperti }}</div>
                            <div class="text-gray-600 text-sm">Properti Saya</div>
                            <a href="{{ route('pemilik.properti') }}" class="text-blue-500 text-xs hover:underline cursor-pointer">
                                Kelola Properti →
                            </a>
                        </div>
                        <div class="text-3xl text-blue-500">🏢</div>
                    </div>
                </div>

                <!-- Active Bookings -->
                <div class="bg-white border border-gray-200 rounded-lg p-4 shadow hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-2xl font-bold text-orange-600">{{ $activeBookings }}</div>
                            <div class="text-gray-600 text-sm">Pemesanan Aktif</div>
                            <a href="{{ route('pemilik.bookings.index') }}" class="text-orange-500 text-xs hover:underline cursor-pointer">
                                Pemesanan →
                            </a>
                        </div>
                        <div class="text-3xl text-orange-500">📋</div>
                    </div>
                </div>

                <!-- Monthly Income -->
                <div class="bg-white border border-gray-200 rounded-lg p-4 shadow hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-2xl font-bold text-green-600">
                                Rp {{ number_format($monthlyIncome / 1000000, 1) }}M
                            </div>
                            <div class="text-gray-600 text-sm">Pendapatan Bulanan</div>
                            <a href="{{ route('pemilik.bookings.index') }}" class="text-green-500 text-xs hover:underline cursor-pointer">
                                Kelola Pemesanan →
                            </a>
                        </div>
                        <div class="text-3xl text-green-500">💰</div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions Alert -->
            @if($pendingActions['new_bookings'] > 0 || $pendingActions['checkout_today'] > 0)
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                <div class="flex">
                    <div class="text-yellow-500 text-xl mr-3">⚠️</div>
                    <div>
                        <div class="font-semibold text-yellow-800">Perlu Tindakan!</div>
                        <div class="text-yellow-700 text-sm">
                            @if($pendingActions['new_bookings'] > 0)
                            • {{ $pendingActions['new_bookings'] }} pemesanan baru menunggu approval
                            @endif
                            @if($pendingActions['checkout_today'] > 0)
                            • {{ $pendingActions['checkout_today'] }} checkout hari ini
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Charts Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <!-- Revenue Chart -->
                <div class="bg-white border border-gray-200 rounded-lg p-6 shadow">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">📈 Tren Pendapatan (12 Bulan)</h3>
                    <div class="h-64">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>

                <!-- Top Properties -->
                <div class="bg-white border border-gray-200 rounded-lg p-6 shadow">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">🏆 Properti Terbaik</h3>
                    <div class="space-y-3">
                        @forelse($propertyStats as $property)
                        <div class="border border-gray-200 rounded p-3 hover:bg-gray-50 transition-colors">
                            <div class="flex justify-between items-center gap-3">
                                <div class="flex items-center gap-3 flex-1">
                                    @if($property->gambar)
                                        @php
                                            $imageData = $property->gambar;
                                            if (!str_starts_with($imageData, 'data:image')) {
                                                $imageData = 'data:image/jpeg;base64,' . $imageData;
                                            }
                                        @endphp
                                        <img src="{{ $imageData }}" alt="{{ $property->nama }}" class="w-16 h-16 object-cover rounded">
                                    @else
                                        <div class="w-16 h-16 bg-gray-200 rounded flex items-center justify-center">
                                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                            </svg>
                                        </div>
                                    @endif
                                    <div>
                                        <div class="font-medium text-gray-800">{{ Str::limit($property->nama, 25) }}</div>
                                        <div class="text-sm text-gray-600">
                                            {{ $property->total_bookings }} bookings • 
                                            ⭐ {{ number_format($property->avg_rating, 1) }}
                                        </div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="font-semibold text-green-600">
                                        Rp {{ number_format($property->total_revenue, 0, ',', '.') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center text-gray-500 py-4">
                            Belum ada data properti
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Activity Section -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                <!-- Recent Bookings -->
                <div class="bg-white border border-gray-200 rounded-lg p-6 shadow">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">📋 Pemesanan Terbaru</h3>
                    <div class="space-y-3">
                        @forelse($recentBookings as $booking)
                        <div class="border border-gray-200 rounded p-3 {{ $booking->status_pemesanan === 'pending' ? 'bg-yellow-50' : ($booking->status_pemesanan === 'confirmed' ? 'bg-green-50' : 'bg-gray-50') }}">
                            <div class="flex justify-between items-start gap-3">
                                <div class="flex items-center gap-3 flex-1">
                                    @if($booking->penyewa->profile_photo)
                                        <img src="{{ asset('storage/' . $booking->penyewa->profile_photo) }}" alt="{{ $booking->penyewa->username }}" class="w-10 h-10 rounded-full object-cover">
                                    @else
                                        <div class="w-10 h-10 rounded-full bg-blue-500 flex items-center justify-center text-white font-semibold">
                                            {{ strtoupper(substr($booking->penyewa->username, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <div class="font-medium text-sm">{{ $booking->penyewa->username }}</div>
                                        <div class="text-xs text-gray-600">{{ Str::limit($booking->properti->nama, 20) }}</div>
                                        <div class="text-xs text-gray-500">{{ $booking->created_at->diffForHumans() }}</div>
                                    </div>
                                </div>
                                <span class="px-2 py-1 text-xs rounded {{ $booking->status_pemesanan === 'pending' ? 'bg-yellow-100 text-yellow-800' : ($booking->status_pemesanan === 'confirmed' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800') }}">
                                    {{ $booking->status_pemesanan === 'confirmed' ? 'Lunas' : ucfirst($booking->status_pemesanan) }}
                                </span>
                            </div>
                        </div>
                        @empty
                        <div class="text-center text-gray-500 py-4">
                            Belum ada pemesanan
                        </div>
                        @endforelse
                        
                        @if($recentBookings->count() > 0)
                        <div class="mt-4">
                            <a href="{{ route('pemilik.bookings.index') }}" class="text-blue-600 text-sm hover:underline cursor-pointer">
                                Lihat semua pemesanan →
                            </a>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Recent Reviews -->
                <div class="bg-white border border-gray-200 rounded-lg p-6 shadow">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">⭐ Review Terbaru</h3>
                    <div class="space-y-3">
                        @forelse($recentReviews as $review)
                        <div class="border border-gray-200 rounded p-3 {{ $review->pemilik_reply ? 'bg-gray-50' : 'bg-purple-50' }}">
                            <div class="flex items-start gap-3 mb-2">
                                @if($review->penyewa->profile_photo)
                                    <img src="{{ asset('storage/' . $review->penyewa->profile_photo) }}" alt="{{ $review->penyewa->username }}" class="w-10 h-10 rounded-full object-cover">
                                @else
                                    <div class="w-10 h-10 rounded-full bg-purple-500 flex items-center justify-center text-white font-semibold text-sm">
                                        {{ strtoupper(substr($review->penyewa->username, 0, 1)) }}
                                    </div>
                                @endif
                                <div class="flex-1">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <div class="font-medium text-sm">{{ $review->penyewa->username }}</div>
                                            <div class="text-yellow-500 text-xs">
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($i <= $review->rating)
                                                        ⭐
                                                    @else
                                                        ☆
                                                    @endif
                                                @endfor
                                            </div>
                                        </div>
                                        <span class="text-xs text-gray-500">{{ $review->created_at->diffForHumans() }}</span>
                                    </div>
                                    <div class="text-xs text-gray-700 mt-1">{{ $review->review ?? 'Tidak ada komentar' }}</div>
                                    @if(!$review->pemilik_reply)
                                    <div class="text-xs text-orange-600 mt-1">⚠️ Perlu dibalas</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center text-gray-500 py-4">
                            Belum ada review
                        </div>
                        @endforelse
                        
                        @if($recentReviews->count() > 0)
                        <div class="mt-4">
                            <a href="{{ route('pemilik.reviews.index') }}" class="text-purple-600 text-sm hover:underline cursor-pointer">
                                Kelola review →
                            </a>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white border border-gray-200 rounded-lg p-6 shadow">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">⚡ Aksi Cepat</h3>
                    <div class="space-y-3">
                        <a href="{{ route('pemilik.properti.create') }}" class="w-full bg-blue-500 text-white text-center py-3 px-4 rounded hover:bg-blue-600 transition-colors text-sm font-medium block">
                            ➕ Tambah Properti Baru
                        </a>
                        
                        <a href="{{ route('pemilik.bookings.index', ['status' => 'pending']) }}" class="w-full bg-orange-500 text-white text-center py-3 px-4 rounded hover:bg-orange-600 transition-colors text-sm font-medium flex items-center justify-center">
                            📋 Tinjau Pemesanan
                            @if($pendingActions['new_bookings'] > 0)
                            <span class="bg-white text-orange-500 px-2 py-1 rounded-full text-xs ml-2">{{ $pendingActions['new_bookings'] }}</span>
                            @endif
                        </a>
                        
                        <a href="{{ route('pemilik.reviews.index') }}" class="w-full bg-purple-500 text-white text-center py-3 px-4 rounded hover:bg-purple-600 transition-colors text-sm font-medium flex items-center justify-center">
                            ⭐ Balas Review
                            @if($pendingReviews > 0)
                            <span class="bg-white text-purple-500 px-2 py-1 rounded-full text-xs ml-2">{{ $pendingReviews }}</span>
                            @endif
                        </a>
                        
                        <a href="{{ route('pemilik.bookings.index') }}" class="w-full bg-green-500 text-white text-center py-3 px-4 rounded hover:bg-green-600 transition-colors text-sm font-medium block">
                            💰 Lihat Laporan Pemesanan
                        </a>
                    </div>
                </div>
            </div>

            <!-- Performance Summary -->
            <div class="bg-white border border-gray-200 rounded-lg p-6 shadow">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">📊 Ringkasan Performa</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-green-600">
                            Rp {{ number_format($pendapatanTahunIni / 1000000, 0) }}M
                        </div>
                        <div class="text-sm text-gray-600">Tahun {{ now()->year }}</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-purple-600">
                            {{ $propertyStats->count() > 0 ? number_format($propertyStats->avg('avg_rating'), 1) : '0.0' }}
                        </div>
                        <div class="text-sm text-gray-600">Rating Rata-rata</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-orange-600">{{ $totalReviews }}</div>
                        <div class="text-sm text-gray-600">Total Review</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart.js Script -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Real data dari controller
        const monthlyData = @json($chartData);
        
        const ctx = document.getElementById('revenueChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: monthlyData.map(item => item.month),
                datasets: [{
                    label: 'Pendapatan',
                    data: monthlyData.map(item => item.revenue),
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
                                return 'Rp ' + (value/1000000).toFixed(0) + 'M';
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
    });
    </script>
</x-app-layout>