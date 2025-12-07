<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Tambah Properti') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('admin.properti.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        <div>
                            <label for="nama" class="block text-sm font-medium mb-2">Nama Properti</label>
                            <input type="text" id="nama" name="nama" value="{{ old('nama') }}" required class="w-full px-4 py-2 border dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded {{ $errors->has('nama') ? 'border-red-500' : 'border-gray-300' }}">
                            @error('nama')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="alamat" class="block text-sm font-medium mb-2">Lokasi</label>
                            <input type="text" id="alamat" name="alamat" value="{{ old('alamat') }}" required class="w-full px-4 py-2 border dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded {{ $errors->has('alamat') ? 'border-red-500' : 'border-gray-300' }}">
                            @error('alamat')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="deskripsi" class="block text-sm font-medium mb-2">Deskripsi</label>
                            <textarea id="deskripsi" name="deskripsi" required class="w-full px-4 py-2 border dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded {{ $errors->has('deskripsi') ? 'border-red-500' : 'border-gray-300' }}">{{ old('deskripsi') }}</textarea>
                            @error('deskripsi')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="harga" class="block text-sm font-medium mb-2">Harga Per Bulan</label>
                            <input type="number" id="harga" name="harga" value="{{ old('harga') }}" required min="0" step="0.01" class="w-full px-4 py-2 border dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded {{ $errors->has('harga') ? 'border-red-500' : 'border-gray-300' }}">
                            @error('harga')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="kamar_tidur" class="block text-sm font-medium mb-2">Kamar Tidur</label>
                                <input type="number" id="kamar_tidur" name="kamar_tidur" value="{{ old('kamar_tidur') }}" min="0" class="w-full px-4 py-2 border dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded {{ $errors->has('kamar_tidur') ? 'border-red-500' : 'border-gray-300' }}">
                                @error('kamar_tidur')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="kamar_mandi" class="block text-sm font-medium mb-2">Kamar Mandi</label>
                                <input type="number" id="kamar_mandi" name="kamar_mandi" value="{{ old('kamar_mandi') }}" min="0" class="w-full px-4 py-2 border dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded {{ $errors->has('kamar_mandi') ? 'border-red-500' : 'border-gray-300' }}">
                                @error('kamar_mandi')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label for="luas_tanah" class="block text-sm font-medium mb-2">Luas Tanah (m²)</label>
                            <input type="number" id="luas_tanah" name="luas_tanah" value="{{ old('luas_tanah') }}" min="0" class="w-full px-4 py-2 border dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded {{ $errors->has('luas_tanah') ? 'border-red-500' : 'border-gray-300' }}">
                            @error('luas_tanah')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="tipe" class="block text-sm font-medium mb-2">Tipe Properti</label>
                            <select id="tipe" name="tipe" class="w-full px-4 py-2 border dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded {{ $errors->has('tipe') ? 'border-red-500' : 'border-gray-300' }}">
                                <option value="">Pilih Tipe</option>
                                <option value="rumah" {{ old('tipe') == 'rumah' ? 'selected' : '' }}>Rumah</option>
                                <option value="apartemen" {{ old('tipe') == 'apartemen' ? 'selected' : '' }}>Apartemen</option>
                                <option value="kontrakan" {{ old('tipe') == 'kontrakan' ? 'selected' : '' }}>Kontrakan</option>
                                <option value="vila" {{ old('tipe') == 'vila' ? 'selected' : '' }}>Vila</option>
                            </select>
                            @error('tipe')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="pemilik_id" class="block text-sm font-medium mb-2">Pemilik</label>
                            <select id="pemilik_id" name="pemilik_id" required class="w-full px-4 py-2 border dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded {{ $errors->has('pemilik_id') ? 'border-red-500' : 'border-gray-300' }}">
                                <option value="">Pilih Pemilik</option>
                                @foreach($pemilik as $p)
                                    <option value="{{ $p->id }}" {{ old('pemilik_id') == $p->id ? 'selected' : '' }}>{{ $p->username }}</option>
                                @endforeach
                            </select>
                            @error('pemilik_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="gambar" class="block text-sm font-medium mb-2">Gambar Properti</label>
                            <input type="file" id="gambar" name="gambar" accept="image/*" required class="w-full px-4 py-2 border dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded {{ $errors->has('gambar') ? 'border-red-500' : 'border-gray-300' }}">
                            <p class="text-xs text-gray-500 mt-1">JPG, PNG, atau JPEG (Max. 10MB)</p>
                            @error('gambar')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="status" class="block text-sm font-medium mb-2">Status</label>
                            <select id="status" name="status" required class="w-full px-4 py-2 border dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded {{ $errors->has('status') ? 'border-red-500' : 'border-gray-300' }}">
                                <option value="tersedia" {{ old('status') == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                                <option value="disewa" {{ old('status') == 'disewa' ? 'selected' : '' }}>Disewa</option>
                            </select>
                            @error('status')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex gap-3">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded transition">
                                Tambah
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
