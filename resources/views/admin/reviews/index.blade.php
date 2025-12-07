<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Kelola Review') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if ($message = Session::get('success'))
                        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                            {{ $message }}
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead class="bg-gray-100 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3">ID</th>
                                    <th class="px-6 py-3">Properti</th>
                                    <th class="px-6 py-3">Penyewa</th>
                                    <th class="px-6 py-3">Rating</th>
                                    <th class="px-6 py-3">Review</th>
                                    <th class="px-6 py-3">Tanggal</th>
                                    <th class="px-6 py-3">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($reviews as $review)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="px-6 py-4">{{ $review->id_review }}</td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                @if($review->properti && $review->properti->gambar)
                                                    <img src="data:image/jpeg;base64,{{ $review->properti->gambar }}" 
                                                         alt="{{ $review->properti->nama ?? 'Properti' }}" 
                                                         class="w-16 h-16 object-cover rounded"
                                                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                                    <div class="w-16 h-16 bg-gray-300 dark:bg-gray-600 rounded items-center justify-center" style="display:none;">
                                                        <span class="text-gray-500 text-xs">No Img</span>
                                                    </div>
                                                @else
                                                    <div class="w-16 h-16 bg-gray-300 dark:bg-gray-600 rounded flex items-center justify-center">
                                                        <span class="text-gray-500 text-xs">No Img</span>
                                                    </div>
                                                @endif
                                                <div>
                                                    <p class="font-medium">{{ $review->properti->nama ?? '-' }}</p>
                                                    @if($review->properti && $review->properti->alamat)
                                                        <p class="text-xs text-gray-500">{{ Str::limit($review->properti->alamat, 30) }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">{{ $review->penyewa->username ?? '-' }}</td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-1">
                                                <span class="text-yellow-400 text-lg">
                                                    @for($i = 0; $i < $review->rating; $i++)
                                                        ⭐
                                                    @endfor
                                                </span>
                                                <span class="text-gray-600 dark:text-gray-400 text-sm ml-1">({{ $review->rating }})</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 max-w-xs truncate">{{ substr($review->review, 0, 50) }}...</td>
                                        <td class="px-6 py-4">{{ $review->created_at->format('d/m/Y') }}</td>
                                        <td class="px-6 py-4">
                                            <div class="flex gap-2">
                                                <a href="{{ route('admin.reviews.show', $review->id_review) }}" 
                                                   class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm">
                                                    Detail
                                                </a>
                                                <form action="{{ route('admin.reviews.destroy', $review->id_review) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus review ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">
                                                        Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">Tidak ada data review</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $reviews->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
