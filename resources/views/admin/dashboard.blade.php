<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <a href="{{ route('admin.properti.index') }}" class="block bg-white shadow hover:shadow-md transition rounded-lg p-6">
                    <div class="text-lg font-semibold">Properti</div>
                    <div class="text-gray-600">Kelola data properti</div>
                </a>
                <a href="{{ route('admin.pemesanan.index') }}" class="block bg-white shadow hover:shadow-md transition rounded-lg p-6">
                    <div class="text-lg font-semibold">Pemesanan</div>
                    <div class="text-gray-600">Lihat & edit status pemesanan</div>
                </a>
                <a href="{{ route('admin.kontrak.index') }}" class="block bg-white shadow hover:shadow-md transition rounded-lg p-6">
                    <div class="text-lg font-semibold">Kontrak</div>
                    <div class="text-gray-600">Lihat semua kontrak</div>
                </a>
                {{-- Review management removed for admin; reviews remain visible on property pages --}}
                <a href="{{ route('admin.akun.index') }}" class="block bg-white shadow hover:shadow-md transition rounded-lg p-6">
                    <div class="text-lg font-semibold">Akun</div>
                    <div class="text-gray-600">Edit akun user</div>
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
