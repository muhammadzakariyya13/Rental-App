<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Properti
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Edit Data Properti</h3>
                    
                    <form action="{{ route('pemilik.properti.update', $properti->id_properti) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Foto Properti -->
                            <div class="col-span-2">
                                <label for="gambar" class="block text-sm font-medium text-gray-700 mb-2">Foto Properti</label>
                                <div class="mt-2">
                                    <!-- Current Image Preview -->
                                    @if($properti->gambar)
                                        <div class="mb-4">
                                            <p class="text-sm text-gray-600 mb-2">Foto saat ini:</p>
                                            @php
                                                $imageSrc = $properti->gambar;
                                                if (!str_starts_with($imageSrc, 'data:image')) {
                                                    $imageSrc = 'data:image/jpeg;base64,' . $imageSrc;
                                                }
                                            @endphp
                                            <img src="{{ $imageSrc }}" 
                                                 alt="Current Photo" 
                                                 class="w-48 h-32 object-cover rounded-lg border-2 border-gray-300"
                                                 id="current-image">
                                        </div>
                                    @endif
                                    
                                    <!-- File Input -->
                                    <input type="file" 
                                           id="gambar" 
                                           name="gambar" 
                                           accept="image/*"
                                           onchange="previewImage(event)"
                                           class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                    <p class="mt-1 text-xs text-gray-500">JPG, PNG, atau JPEG (Max. 2MB). Biarkan kosong jika tidak ingin mengubah foto.</p>
                                    
                                    <!-- New Image Preview -->
                                    <img id="preview-image" 
                                         src="" 
                                         alt="Preview" 
                                         class="hidden mt-4 w-48 h-32 object-cover rounded-lg border-2 border-blue-500">
                                </div>
                                <x-input-error class="mt-2" :messages="$errors->get('gambar')" />
                            </div>

                            <script>
                            function previewImage(event) {
                                const file = event.target.files[0];
                                if (file) {
                                    const reader = new FileReader();
                                    reader.onload = function(e) {
                                        const preview = document.getElementById('preview-image');
                                        preview.src = e.target.result;
                                        preview.classList.remove('hidden');
                                        
                                        // Hide current image
                                        const currentImage = document.getElementById('current-image');
                                        if (currentImage) {
                                            currentImage.classList.add('opacity-50');
                                        }
                                    }
                                    reader.readAsDataURL(file);
                                }
                            }
                            </script>

                            <!-- Nama Properti -->
                            <div class="col-span-2">
                                <label for="nama" class="block text-sm font-medium text-gray-700">Nama Properti</label>
                                <input type="text" name="nama" id="nama" 
                                       value="{{ $properti->nama }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                                       required>
                            </div>

                            <!-- Alamat -->
                            <div class="col-span-2">
                                <label for="alamat" class="block text-sm font-medium text-gray-700">Alamat</label>
                                <textarea name="alamat" id="alamat" rows="3" 
                                          class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                                          required>{{ $properti->alamat }}</textarea>
                            </div>

                            <!-- Harga -->
                            <div>
                                <label for="harga" class="block text-sm font-medium text-gray-700">Harga per Bulan</label>
                                <input type="number" name="harga" id="harga" 
                                       value="{{ $properti->harga }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                                       required>
                            </div>

                            <!-- Tipe -->
                            <div>
                                <label for="tipe" class="block text-sm font-medium text-gray-700">Tipe Properti</label>
                                <select name="tipe" id="tipe" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                                    <option value="rumah" {{ $properti->tipe == 'rumah' ? 'selected' : '' }}>Rumah</option>
                                    <option value="apartemen" {{ $properti->tipe == 'apartemen' ? 'selected' : '' }}>Apartemen</option>
                                    <option value="kontrakan" {{ $properti->tipe == 'kontrakan' ? 'selected' : '' }}>Kontrakan</option>
                                    <option value="vila" {{ $properti->tipe == 'vila' ? 'selected' : '' }}>Vila</option>
                                </select>
                            </div>

                            <!-- Kamar Tidur -->
                            <div>
                                <label for="kamar_tidur" class="block text-sm font-medium text-gray-700">Kamar Tidur</label>
                                <input type="number" name="kamar_tidur" id="kamar_tidur" 
                                       value="{{ $properti->kamar_tidur }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                            </div>

                            <!-- Kamar Mandi -->
                            <div>
                                <label for="kamar_mandi" class="block text-sm font-medium text-gray-700">Kamar Mandi</label>
                                <input type="number" name="kamar_mandi" id="kamar_mandi" 
                                       value="{{ $properti->kamar_mandi }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                            </div>

                            <!-- Luas Tanah -->
                            <div>
                                <label for="luas_tanah" class="block text-sm font-medium text-gray-700">Luas Tanah (m²)</label>
                                <input type="number" name="luas_tanah" id="luas_tanah" 
                                       value="{{ $properti->luas_tanah }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            </div>

                            <!-- Luas Bangunan -->
                            <div>
                                <label for="luas_bangunan" class="block text-sm font-medium text-gray-700">Luas Bangunan (m²)</label>
                                <input type="number" name="luas_bangunan" id="luas_bangunan" 
                                       value="{{ $properti->luas_bangunan }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            </div>

                            <!-- Status -->
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                                <select name="status" id="status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                                    <option value="tersedia" {{ $properti->status == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                                    <option value="disewa" {{ $properti->status == 'disewa' ? 'selected' : '' }}>Disewa</option>
                                </select>
                            </div>

                            <!-- Deskripsi -->
                            <div class="col-span-2">
                                <label for="deskripsi" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                                <textarea name="deskripsi" id="deskripsi" rows="4" 
                                          class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ $properti->deskripsi }}</textarea>
                            </div>

                            <!-- Submit Button -->
                            <div class="col-span-2 flex justify-end space-x-3">
                                <a href="{{ route('pemilik.properti') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Batal</a>
                                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Update Properti</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>