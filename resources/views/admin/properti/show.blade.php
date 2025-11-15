<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Detail Properti') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-medium mb-2">Nama Properti</label>
                            <p class="text-lg font-semibold">{{ $properti->nama_properti }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2">Lokasi</label>
                            <p class="text-lg">{{ $properti->lokasi }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2">Deskripsi</label>
                            <p class="text-lg">{{ $properti->deskripsi }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2">Harga Per Hari</label>
                            <p class="text-lg font-bold text-green-600">Rp {{ number_format($properti->harga_perhari, 0, ',', '.') }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2">Status</label>
                            <span class="px-3 py-1 rounded text-sm font-medium {{ $properti->status == 'tersedia' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ ucfirst($properti->status) }}
                            </span>
                        </div>

                        <div class="flex gap-3">
                            <a href="{{ route('admin.properti.edit', $properti->id_properti) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded transition">
                                Edit
                            </a>
                            <a href="{{ route('admin.properti.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded transition">
                                Kembali
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
