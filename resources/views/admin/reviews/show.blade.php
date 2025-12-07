<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Detail Review') }}
            </h2>
            <a href="{{ route('admin.reviews.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    {{-- Properti Info --}}
                    <div class="mb-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <h3 class="text-lg font-semibold mb-4">Informasi Properti</h3>
                        
                        {{-- Gambar Properti --}}
                        @if($review->properti && $review->properti->gambar)
                            <div class="mb-4">
                                <img src="{{ asset($review->properti->gambar) }}" 
                                     alt="{{ $review->properti->nama }}" 
                                     class="w-full h-48 object-cover rounded-lg">
                            </div>
                        @endif
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Nama Properti</p>
                                <p class="font-medium">{{ $review->properti->nama ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Lokasi</p>
                                <p class="font-medium">{{ $review->properti->alamat ?? '-' }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Reviewer Info --}}
                    <div class="mb-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <h3 class="text-lg font-semibold mb-2">Informasi Penyewa</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Username</p>
                                <p class="font-medium">{{ $review->penyewa->username ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Email</p>
                                <p class="font-medium">{{ $review->penyewa->email ?? '-' }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Review Content --}}
                    <div class="mb-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <h3 class="text-lg font-semibold mb-3">Review</h3>
                        
                        <div class="mb-3">
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Rating</p>
                            <div class="text-yellow-500 text-xl">
                                @for($i = 0; $i < $review->rating; $i++)
                                    <i class="fas fa-star"></i>
                                @endfor
                                @for($i = $review->rating; $i < 5; $i++)
                                    <i class="far fa-star"></i>
                                @endfor
                                <span class="text-gray-700 dark:text-gray-300 text-base ml-2">{{ $review->rating }}/5</span>
                            </div>
                        </div>

                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Ulasan</p>
                            <p class="text-gray-800 dark:text-gray-200">{{ $review->review }}</p>
                        </div>
                    </div>

                    {{-- Timestamps --}}
                    <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <h3 class="text-lg font-semibold mb-3">Informasi Lainnya</h3>
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <p class="text-gray-600 dark:text-gray-400">Tanggal Review</p>
                                <p class="font-medium">{{ $review->created_at->format('d M Y H:i') }}</p>
                            </div>
                            <div>
                                <p class="text-gray-600 dark:text-gray-400">Terakhir Diupdate</p>
                                <p class="font-medium">{{ $review->updated_at->format('d M Y H:i') }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="mt-6 flex gap-3">
                        <form action="{{ route('admin.reviews.destroy', $review->id_review) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus review ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded">
                                <i class="fas fa-trash mr-2"></i>Hapus Review
                            </button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
