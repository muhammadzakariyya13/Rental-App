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
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold mb-2">👋 Selamat datang, {{ Auth::user()->name ?? Auth::user()->username }}!</h1>
                        <p class="text-blue-100">Kelola properti dan pemesanan Anda dengan mudah dari dashboard ini.</p>
                    </div>
                    <div class="text-right hidden md:block">
                        <div class="text-2xl font-bold">{{ number_format($occupancyRate ?? 75, 1) }}%</div>
                        <div class="text-blue-200 text-sm">Occupancy Rate</div>
                    </div>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <!-- Total Properties -->
                <div class="bg-white border border-gray-200 rounded-lg p-4 shadow hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-2xl font-bold text-blue-600">{{ $totalProperti ?? 1 }}</div>
                            <div class="text-gray-600 text-sm">My Properties</div>
                            <div class="text-blue-500 text-xs hover:underline cursor-pointer">
                                Kelola Properti →
                            </div>
                        </div>
                        <div class="text-3xl text-blue-500">🏢</div>
                    </div>
                </div>

                <!-- Active Bookings -->
                <div class="bg-white border border-gray-200 rounded-lg p-4 shadow hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-2xl font-bold text-orange-600">{{ $activeBookings ?? 0 }}</div>
                            <div class="text-gray-600 text-sm">Active Bookings</div>
                            <div class="text-orange-500 text-xs hover:underline cursor-pointer">
                                Pemesanan →
                            </div>
                        </div>
                        <div class="text-3xl text-orange-500">📋</div>
                    </div>
                </div>

                <!-- Monthly Income -->
                <div class="bg-white border border-gray-200 rounded-lg p-4 shadow hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-2xl font-bold text-green-600">
                                Rp {{ number_format(($monthlyIncome ?? 15000000) / 1000000, 1) }}M
                            </div>
                            <div class="text-gray-600 text-sm">Monthly Income</div>
                            <div class="text-green-500 text-xs hover:underline cursor-pointer">
                                Laporan Pendapatan →
                            </div>
                        </div>
                        <div class="text-3xl text-green-500">💰</div>
                    </div>
                </div>

                <!-- Pending Reviews -->
                <div class="bg-white border border-gray-200 rounded-lg p-4 shadow hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-2xl font-bold text-purple-600">{{ $pendingReviews ?? 8 }}</div>
                            <div class="text-gray-600 text-sm">Pending Reviews</div>
                            <div class="text-purple-500 text-xs hover:underline cursor-pointer">
                                Manage Properties →
                            </div>
                        </div>
                        <div class="text-3xl text-purple-500">⭐</div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions Alert -->
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                <div class="flex">
                    <div class="text-yellow-500 text-xl mr-3">⚠️</div>
                    <div>
                        <div class="font-semibold text-yellow-800">Action Required!</div>
                        <div class="text-yellow-700 text-sm">
                            • {{ $pendingActions['new_bookings'] ?? 3 }} pemesanan baru menunggu approval
                            • {{ $pendingActions['checkout_today'] ?? 2 }} checkout hari ini
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <!-- Revenue Chart -->
                <div class="bg-white border border-gray-200 rounded-lg p-6 shadow">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">📈 Revenue Trend (6 Months)</h3>
                    <div class="h-64">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>

                <!-- Top Properties -->
                <div class="bg-white border border-gray-200 rounded-lg p-6 shadow">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">🏆 Top Properties</h3>
                    <div class="space-y-3">
                        @if(isset($propertyStats) && count($propertyStats) > 0)
                            @foreach($propertyStats as $property)
                            <div class="border border-gray-200 rounded p-3 hover:bg-gray-50 transition-colors">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <div class="font-medium text-gray-800">{{ Str::limit($property->nama ?? 'Villa Batu', 25) }}</div>
                                        <div class="text-sm text-gray-600">
                                            {{ $property->total_bookings ?? 15 }} bookings • 
                                            ⭐ {{ number_format($property->avg_rating ?? 4.5, 1) }}
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <div class="font-semibold text-green-600">
                                            Rp {{ number_format($property->total_revenue ?? 25000000, 0, ',', '.') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        @else
                            <!-- Sample Data -->
                            <div class="border border-gray-200 rounded p-3 hover:bg-gray-50 transition-colors">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <div class="font-medium text-gray-800">Villa Batu Malang</div>
                                        <div class="text-sm text-gray-600">15 bookings • ⭐ 4.8</div>
                                    </div>
                                    <div class="text-right">
                                        <div class="font-semibold text-green-600">Rp 25,000,000</div>
                                    </div>
                                </div>
                            </div>
                            <div class="border border-gray-200 rounded p-3 hover:bg-gray-50 transition-colors">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <div class="font-medium text-gray-800">Apartemen Jakarta</div>
                                        <div class="text-sm text-gray-600">12 bookings • ⭐ 4.6</div>
                                    </div>
                                    <div class="text-right">
                                        <div class="font-semibold text-green-600">Rp 18,000,000</div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Activity Section -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                <!-- Recent Bookings -->
                <div class="bg-white border border-gray-200 rounded-lg p-6 shadow">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">📋 Recent Bookings</h3>
                    <div class="space-y-3">
                        @if(isset($recentBookings) && count($recentBookings) > 0)
                            @foreach($recentBookings as $booking)
                            <div class="border border-gray-200 rounded p-3 {{ $booking->status === 'pending' ? 'bg-yellow-50' : 'bg-green-50' }}">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <div class="font-medium text-sm">{{ $booking->penyewa->username ?? 'Guest' }}</div>
                                        <div class="text-xs text-gray-600">{{ Str::limit($booking->properti->nama ?? 'Property', 20) }}</div>
                                        <div class="text-xs text-gray-500">{{ $booking->created_at->diffForHumans() ?? '2 hours ago' }}</div>
                                    </div>
                                    <span class="px-2 py-1 text-xs rounded {{ $booking->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">
                                        {{ ucfirst($booking->status ?? 'confirmed') }}
                                    </span>
                                </div>
                            </div>
                            @endforeach
                        @else
                            <!-- Sample Recent Bookings -->
                            <div class="border border-gray-200 rounded p-3 bg-yellow-50">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <div class="font-medium text-sm">John Doe</div>
                                        <div class="text-xs text-gray-600">Villa Batu Malang</div>
                                        <div class="text-xs text-gray-500">2 hours ago</div>
                                    </div>
                                    <span class="px-2 py-1 text-xs rounded bg-yellow-100 text-yellow-800">Pending</span>
                                </div>
                            </div>
                            <div class="border border-gray-200 rounded p-3 bg-green-50">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <div class="font-medium text-sm">Jane Smith</div>
                                        <div class="text-xs text-gray-600">Apartemen Jakarta</div>
                                        <div class="text-xs text-gray-500">1 day ago</div>
                                    </div>
                                    <span class="px-2 py-1 text-xs rounded bg-green-100 text-green-800">Confirmed</span>
                                </div>
                            </div>
                        @endif
                        
                        <div class="mt-4">
                            <div class="text-blue-600 text-sm hover:underline cursor-pointer">
                                View all bookings →
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Reviews -->
                <div class="bg-white border border-gray-200 rounded-lg p-6 shadow">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">⭐ Recent Reviews</h3>
                    <div class="space-y-3">
                        <!-- Sample Reviews -->
                        <div class="border border-gray-200 rounded p-3 bg-purple-50">
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <div class="font-medium text-sm">Alice Johnson</div>
                                    <div class="text-yellow-500 text-xs">⭐⭐⭐⭐⭐</div>
                                </div>
                                <span class="text-xs text-gray-500">1 day ago</span>
                            </div>
                            <div class="text-xs text-gray-700">Great place! Very clean and comfortable...</div>
                            <div class="text-xs text-orange-600 mt-1">⚠️ Needs reply</div>
                        </div>
                        
                        <div class="border border-gray-200 rounded p-3 bg-purple-50">
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <div class="font-medium text-sm">Bob Wilson</div>
                                    <div class="text-yellow-500 text-xs">⭐⭐⭐⭐☆</div>
                                </div>
                                <span class="text-xs text-gray-500">2 days ago</span>
                            </div>
                            <div class="text-xs text-gray-700">Nice location, friendly host...</div>
                        </div>
                        
                        <div class="mt-4">
                            <div class="text-purple-600 text-sm hover:underline cursor-pointer">
                                Manage reviews →
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white border border-gray-200 rounded-lg p-6 shadow">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">⚡ Quick Actions</h3>
                    <div class="space-y-3">
                        <button class="w-full bg-blue-500 text-white text-center py-3 px-4 rounded hover:bg-blue-600 transition-colors text-sm font-medium">
                            ➕ Add New Property
                        </button>
                        
                        <button class="w-full bg-orange-500 text-white text-center py-3 px-4 rounded hover:bg-orange-600 transition-colors text-sm font-medium">
                            📋 Review Bookings
                            <span class="bg-white text-orange-500 px-2 py-1 rounded-full text-xs ml-2">3</span>
                        </button>
                        
                        <button class="w-full bg-purple-500 text-white text-center py-3 px-4 rounded hover:bg-purple-600 transition-colors text-sm font-medium">
                            ⭐ Reply to Reviews
                            <span class="bg-white text-purple-500 px-2 py-1 rounded-full text-xs ml-2">8</span>
                        </button>
                        
                        <button class="w-full bg-green-500 text-white text-center py-3 px-4 rounded hover:bg-green-600 transition-colors text-sm font-medium">
                            💰 View Income Report
                        </button>
                    </div>
                </div>
            </div>

            <!-- Performance Summary -->
            <div class="bg-white border border-gray-200 rounded-lg p-6 shadow">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">📊 Performance Summary</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-blue-600">{{ number_format($occupancyRate ?? 75, 1) }}%</div>
                        <div class="text-sm text-gray-600">Occupancy Rate</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-green-600">Rp 150M</div>
                        <div class="text-sm text-gray-600">Tahun {{ now()->year }}</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-purple-600">4.7</div>
                        <div class="text-sm text-gray-600">Avg Rating</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-orange-600">142</div>
                        <div class="text-sm text-gray-600">Total Reviews</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart.js Script -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Sample data untuk chart
        const monthlyData = [
            { month: 'Jul', revenue: 12000000 },
            { month: 'Aug', revenue: 15000000 },
            { month: 'Sep', revenue: 18000000 },
            { month: 'Oct', revenue: 14000000 },
            { month: 'Nov', revenue: 20000000 },
            { month: 'Dec', revenue: 25000000 }
        ];
        
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