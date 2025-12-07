{{-- filepath: D:\PROJEK LARAVEL\Rental-App\resources\views\pemilik\reviews\index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                ⭐ Review Management
            </h2>
            <div class="flex items-center space-x-3">
                <button onclick="refreshStats()" class="bg-blue-500 text-white px-3 py-1 rounded text-sm hover:bg-blue-600 transition">
                    🔄 Refresh
                </button>
                <div class="text-sm bg-blue-100 text-blue-800 px-3 py-1 rounded-full">
                    ⭐ {{ $stats['average_rating'] }} Rating
                </div>
                <div class="text-sm bg-green-100 text-green-800 px-3 py-1 rounded-full">
                    {{ $stats['total_reviews'] }} Total Reviews
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    {{ session('success') }}
                    <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.remove()">×</button>
                </div>
            @endif

            <!-- Stats Dashboard -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <!-- Total Reviews -->
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 text-white p-4 rounded-lg shadow-lg transform hover:scale-105 transition duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-blue-100 text-xs uppercase tracking-wide">Total</p>
                            <p class="text-2xl font-bold">{{ $stats['total_reviews'] }}</p>
                        </div>
                        <div class="text-2xl">📝</div>
                    </div>
                </div>

                <!-- Average Rating -->
                <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 text-white p-4 rounded-lg shadow-lg transform hover:scale-105 transition duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-yellow-100 text-xs uppercase tracking-wide">Rating</p>
                            <p class="text-2xl font-bold">{{ $stats['average_rating'] }}</p>
                        </div>
                        <div class="text-2xl">⭐</div>
                    </div>
                </div>

                <!-- Pending Replies -->
                <div class="bg-gradient-to-br from-orange-500 to-orange-600 text-white p-4 rounded-lg shadow-lg transform hover:scale-105 transition duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-orange-100 text-xs uppercase tracking-wide">Pending</p>
                            <p class="text-2xl font-bold">{{ $stats['pending_replies'] }}</p>
                        </div>
                        <div class="text-2xl">💬</div>
                    </div>
                </div>

                <!-- This Month -->
                <div class="bg-gradient-to-br from-green-500 to-green-600 text-white p-4 rounded-lg shadow-lg transform hover:scale-105 transition duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-green-100 text-xs uppercase tracking-wide">Bulan Ini</p>
                            <p class="text-2xl font-bold">{{ $stats['reviews_bulan_ini'] }}</p>
                        </div>
                        <div class="text-2xl">📊</div>
                    </div>
                </div>
            </div>

            <!-- Charts Row -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <!-- Rating Distribution -->
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">📊 Rating Distribution</h3>
                    @for($i = 5; $i >= 1; $i--)
                    <div class="flex items-center mb-2">
                        <div class="w-8 text-sm">{{ $i }}⭐</div>
                        <div class="flex-1 mx-3">
                            <div class="bg-gray-200 rounded-full h-3">
                                <div class="bg-gradient-to-r from-yellow-400 to-yellow-500 h-3 rounded-full transition-all duration-700" 
                                     style="width: {{ $ratingDistribution[$i]['percentage'] }}%"></div>
                            </div>
                        </div>
                        <div class="w-20 text-sm text-right">
                            {{ $ratingDistribution[$i]['count'] }} ({{ number_format($ratingDistribution[$i]['percentage'], 1) }}%)
                        </div>
                    </div>
                    @endfor
                </div>

                <!-- Monthly Trend -->
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">📈 6 Month Trend</h3>
                    <div class="h-48">
                        <canvas id="monthlyChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Filters & Search -->
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <form method="GET" class="grid grid-cols-1 md:grid-cols-6 gap-4">
                    <div>
                        <input type="text" name="search" value="{{ request('search') }}" 
                               placeholder="🔍 Search reviews..." 
                               class="w-full rounded-md border-gray-300 text-sm">
                    </div>
                    
                    <div>
                        <select name="rating" class="w-full rounded-md border-gray-300 text-sm">
                            <option value="">All Ratings</option>
                            @for($i = 5; $i >= 1; $i--)
                                <option value="{{ $i }}" {{ request('rating') == $i ? 'selected' : '' }}>
                                    {{ $i }} ⭐
                                </option>
                            @endfor
                        </select>
                    </div>

                    <div>
                        <select name="status" class="w-full rounded-md border-gray-300 text-sm">
                            <option value="">All Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>🔴 Need Reply</option>
                            <option value="replied" {{ request('status') == 'replied' ? 'selected' : '' }}>✅ Replied</option>
                        </select>
                    </div>

                    <div>
                        <select name="properti" class="w-full rounded-md border-gray-300 text-sm">
                            <option value="">All Properties</option>
                            @foreach($propertiList as $prop)
                                <option value="{{ $prop->id_properti }}" {{ request('properti') == $prop->id_properti ? 'selected' : '' }}>
                                    {{ Str::limit($prop->nama, 20) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <button type="submit" class="w-full bg-blue-500 text-white px-4 py-2 rounded text-sm hover:bg-blue-600 transition">
                            🔍 Filter
                        </button>
                    </div>

                    <div>
                        <a href="{{ route('pemilik.reviews.index') }}" class="w-full bg-gray-500 text-white px-4 py-2 rounded text-sm hover:bg-gray-600 transition block text-center">
                            🔄 Reset
                        </a>
                    </div>
                </form>

                <!-- Bulk Actions -->
                <div class="mt-4 pt-4 border-t border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <button type="button" onclick="selectAll()" class="text-sm text-blue-600 hover:text-blue-800">
                                ☑️ Select All
                            </button>
                            <button type="button" onclick="selectNone()" class="text-sm text-gray-600 hover:text-gray-800">
                                ⬜ Select None
                            </button>
                            <span id="selectedCount" class="text-sm text-gray-500">0 selected</span>
                        </div>
                        
                        <div class="flex space-x-2">
                            <button onclick="bulkAction('delete')" class="bg-red-500 text-white px-3 py-1 rounded text-sm hover:bg-red-600 transition">
                                🗑️ Delete Selected
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Reviews List -->
            <div class="bg-white overflow-hidden shadow-lg rounded-lg">
                @if($reviews->count() > 0)
                    <div class="divide-y divide-gray-200">
                        @foreach($reviews as $review)
                        <div class="p-6 hover:bg-gray-50 transition">
                            <div class="flex items-start space-x-4">
                                <!-- Selection Checkbox -->
                                <div class="pt-1">
                                    <input type="checkbox" class="review-checkbox rounded" value="{{ $review->id_review }}" 
                                           onchange="updateSelectedCount()">
                                </div>

                                <!-- Avatar -->
                                <div class="flex-shrink-0">
                                    @if($review->penyewa && $review->penyewa->profile_photo)
                                        @php
                                            $profilePhoto = $review->penyewa->profile_photo;
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
                                        <img src="{{ $profileSrc }}" alt="Profile" class="h-12 w-12 rounded-full object-cover">
                                    @else
                                        <div class="h-12 w-12 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center">
                                            <span class="text-white font-bold">
                                                {{ strtoupper(substr($review->penyewa->username ?? 'U', 0, 1)) }}
                                            </span>
                                        </div>
                                    @endif
                                </div>

                                <!-- Content -->
                                <div class="flex-1">
                                    <!-- Header -->
                                    <div class="flex items-center justify-between mb-3">
                                        <div class="flex items-center space-x-3">
                                            <!-- Property Image -->
                                            <div class="flex-shrink-0">
                                                @if($review->properti && $review->properti->gambar)
                                                    <img src="{{ asset($review->properti->gambar) }}" alt="{{ $review->properti->nama }}" class="h-16 w-20 object-cover rounded">
                                                @else
                                                    <div class="h-16 w-20 bg-gray-200 rounded flex items-center justify-center">
                                                        <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                                        </svg>
                                                    </div>
                                                @endif
                                            </div>
                                            
                                            <div>
                                                <h4 class="text-lg font-semibold text-gray-900">{{ $review->penyewa->username ?? 'Anonymous' }}</h4>
                                                <p class="text-sm text-gray-600">
                                                    🏠 {{ $review->properti->nama ?? 'Property' }} • 
                                                    📅 {{ $review->tanggal_review->format('d M Y') }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="text-yellow-500 text-lg">
                                            {{ str_repeat('⭐', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}
                                        </div>
                                    </div>

                                    <!-- Review Text -->
                                    <div class="mb-4">
                                        <p class="text-gray-800 leading-relaxed">{{ $review->review }}</p>
                                    </div>

                                    <!-- Reply Section -->
                                    @if($review->has_reply)
                                        <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-4 rounded">
                                            <div class="flex justify-between items-start">
                                                <div class="flex-1">
                                                    <p class="text-sm font-medium text-blue-800 mb-1">💬 Your Reply:</p>
                                                    <p class="text-blue-700">{{ $review->pemilik_reply }}</p>
                                                </div>
                                                <span class="text-xs text-blue-600 ml-2">{{ $review->reply_date->diffForHumans() }}</span>
                                            </div>
                                        </div>
                                    @else
                                        <form action="{{ route('pemilik.reviews.reply', $review->id_review) }}" method="POST" class="mb-4">
                                            @csrf
                                            <div class="flex space-x-2">
                                                <textarea name="pemilik_reply" placeholder="💬 Write your reply..." 
                                                          class="flex-1 rounded-md border-gray-300 text-sm resize-none" 
                                                          rows="2" required></textarea>
                                                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition">
                                                    Send
                                                </button>
                                            </div>
                                        </form>
                                    @endif

                                    <!-- Action Buttons -->
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-4 text-sm text-gray-500">
                                            <span>⏰ {{ $review->created_at->diffForHumans() }}</span>
                                            @if($review->has_reply)
                                                <span class="px-2 py-1 rounded text-xs bg-green-100 text-green-800">
                                                    💬 Replied
                                                </span>
                                            @else
                                                <span class="px-2 py-1 rounded text-xs bg-yellow-100 text-yellow-800">
                                                    ⏳ Pending Reply
                                                </span>
                                            @endif
                                        </div>
                                        
                                        <div class="flex space-x-2">
                                            <form action="{{ route('pemilik.reviews.destroy', $review->id_review) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800 text-sm" 
                                                        onclick="return confirm('🗑️ Delete this review?')">
                                                    🗑️ Delete
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $reviews->appends(request()->query())->links() }}
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="text-6xl mb-4">⭐</div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No Reviews Yet</h3>
                        <p class="text-gray-500">Reviews will appear here after guests rate your properties.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Bulk Action Modal - SAME AS BEFORE -->
    <!-- Chart.js Script - SAME AS BEFORE -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // SAME JAVASCRIPT AS BEFORE - JUST UPDATE ROUTE NAME
        // Change "pemilik.reviews.bulkAction" to "pemilik.reviews.bulkAction"
    </script>
</x-app-layout>