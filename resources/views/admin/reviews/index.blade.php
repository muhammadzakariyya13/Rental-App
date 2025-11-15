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
                                    <th class="px-6 py-3">Approved</th>
                                    <th class="px-6 py-3">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($reviews as $review)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="px-6 py-4">{{ $review->id_review }}</td>
                                        <td class="px-6 py-4">{{ $review->properti->nama_properti ?? '-' }}</td>
                                        <td class="px-6 py-4">{{ $review->penyewa->username ?? '-' }}</td>
                                        <td class="px-6 py-4">
                                            <span class="text-yellow-500">
                                                @for($i = 0; $i < $review->rating; $i++)
                                                    <i class="fas fa-star"></i>
                                                @endfor
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 max-w-xs truncate">{{ substr($review->review, 0, 50) }}...</td>
                                        <td class="px-6 py-4">
                                            <span class="px-2 py-1 rounded text-xs font-medium 
                                                {{ $review->is_approved ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $review->is_approved ? 'Approved' : 'Pending' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 space-x-2">
                                            <a href="{{ route('admin.reviews.show', $review->id_review) }}" class="text-blue-500 hover:text-blue-700">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.reviews.edit', $review->id_review) }}" class="text-yellow-500 hover:text-yellow-700">
                                                <i class="fas fa-check-circle"></i>
                                            </a>
                                            <form action="{{ route('admin.reviews.destroy', $review->id_review) }}" method="POST" class="inline" onclick="return confirm('Yakin ingin menghapus?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-500 hover:text-red-700">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
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
