<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Properti') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('admin.properti.update', $properti->id_properti) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div>
                            <label for="nama" class="block text-sm font-medium mb-2">Nama Properti</label>
                            <input type="text" id="nama" name="nama" value="{{ $properti->nama }}" required class="w-full px-4 py-2 border dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded {{ $errors->has('nama') ? 'border-red-500' : 'border-gray-300' }}">
                            @error('nama')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="alamat" class="block text-sm font-medium mb-2">Lokasi</label>
                            <input type="text" id="alamat" name="alamat" value="{{ $properti->alamat }}" required class="w-full px-4 py-2 border dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded {{ $errors->has('alamat') ? 'border-red-500' : 'border-gray-300' }}">
                            @error('alamat')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="deskripsi" class="block text-sm font-medium mb-2">Deskripsi</label>
                            <textarea id="deskripsi" name="deskripsi" required class="w-full px-4 py-2 border dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded {{ $errors->has('deskripsi') ? 'border-red-500' : 'border-gray-300' }}">{{ $properti->deskripsi }}</textarea>
                            @error('deskripsi')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="harga" class="block text-sm font-medium mb-2">Harga Per Hari</label>
                            <input type="number" id="harga" name="harga" value="{{ $properti->harga }}" required min="0" step="0.01" class="w-full px-4 py-2 border dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded {{ $errors->has('harga') ? 'border-red-500' : 'border-gray-300' }}">
                            @error('harga')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="gambar" class="block text-sm font-medium mb-2">Gambar Properti</label>
                            @if($properti->gambar)
                                <div class="mb-3">
                                    <img src="data:image/jpeg;base64,{{ $properti->gambar }}" alt="{{ $properti->nama }}" class="w-32 h-32 rounded object-cover">
                                </div>
                            @endif
                            <input type="file" id="gambar" name="gambar" accept="image/*" class="w-full px-4 py-2 border dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded {{ $errors->has('gambar') ? 'border-red-500' : 'border-gray-300' }}">
                            @error('gambar')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="status" class="block text-sm font-medium mb-2">Status</label>
                            <select id="status" name="status" required class="w-full px-4 py-2 border dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded {{ $errors->has('status') ? 'border-red-500' : 'border-gray-300' }}">
                                <option value="tersedia" {{ $properti->status == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                                <option value="disewa" {{ $properti->status == 'disewa' ? 'selected' : '' }}>Disewa</option>
                                <option value="maintenance" {{ $properti->status == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                            </select>
                            @error('status')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex gap-3">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded transition">
                                Simpan
                            </button>
                            <a href="{{ route('admin.properti.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded transition">
                                Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
