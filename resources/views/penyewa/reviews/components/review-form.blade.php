<form method="POST" action="{{ route('penyewa.reviews.store') }}" class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-8">
    @csrf

    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-6">✍️ Tulis Review</h3>

    <!-- Property Select -->
    <div class="mb-6">
        <label for="properti" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            Pilih Properti
        </label>
        <select id="properti" name="properti_id" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 outline-none transition-colors @error('properti_id') border-red-500 @enderror">
            <option value="">-- Pilih Properti --</option>
            @foreach($properties as $prop)
                <option value="{{ $prop->id_properti }}">{{ $prop->nama_properti }}</option>
            @endforeach
        </select>
        @error('properti_id')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    <!-- Rating Selection -->
    <div class="mb-6">
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
            Rating
        </label>
        <div class="flex gap-3">
            @for($i = 1; $i <= 5; $i++)
                <label class="flex items-center cursor-pointer">
                    <input type="radio" name="rating" value="{{ $i }}" class="w-4 h-4 text-yellow-500" required>
                    <span class="ml-2 text-2xl">
                        @for($j = 0; $j < $i; $j++)⭐@endfor
                    </span>
                </label>
            @endfor
        </div>
        @error('rating')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    <!-- Review Text -->
    <div class="mb-6">
        <label for="review" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            Ulasan Anda
        </label>
        <textarea id="review" name="review" rows="5" placeholder="Bagikan pengalaman Anda menginap di properti ini..." required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 outline-none transition-colors @error('review') border-red-500 @enderror"></textarea>
        @error('review')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    <!-- Submit Button -->
    <button type="submit" class="w-full px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200">
        📤 Kirim Review
    </button>
</form>
