<div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow duration-300">
    <!-- Header -->
    <div class="flex justify-between items-start mb-4">
        <div>
            <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-100">
                {{ $nama_properti }}
            </h4>
            <p class="text-sm text-gray-600 dark:text-gray-400">
                📅 {{ $tanggal }}
            </p>
            @if(!$is_approved)
                <span class="inline-block mt-2 px-2 py-1 bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200 text-xs font-medium rounded">
                    ⏳ Menunggu Persetujuan
                </span>
            @endif
        </div>
        <div class="flex gap-2">
            <a href="{{ route('penyewa.reviews.edit', $id) }}" class="px-3 py-1 text-sm text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900 rounded">
                ✏️ Edit
            </a>
            <form method="POST" action="{{ route('penyewa.reviews.destroy', $id) }}" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" onclick="return confirm('Hapus review ini?')" class="px-3 py-1 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900 rounded">
                    🗑️ Hapus
                </button>
            </form>
        </div>
    </div>

    <!-- Rating Stars -->
    <div class="flex items-center gap-3 mb-4">
        <div class="text-xl">
            @for($i = 0; $i < $rating; $i++)
                ⭐
            @endfor
            @for($i = $rating; $i < 5; $i++)
                ☆
            @endfor
        </div>
        <span class="text-sm font-medium text-gray-600 dark:text-gray-400">
            {{ $rating }}/5
        </span>
    </div>

    <!-- Review Text -->
    <p class="text-gray-700 dark:text-gray-300 leading-relaxed">
        {{ $review_text }}
    </p>
</div>
