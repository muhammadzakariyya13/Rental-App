@extends('layouts.app')
@section('content')
<div class="container mx-auto p-6">
  <h1 class="text-xl font-semibold mb-6">Daftar Kontrak</h1>
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($items as $it)
      <div class="bg-white rounded-lg shadow p-4">
        <div class="text-sm text-gray-600">ID: {{ $it->id_kontrak }}</div>
        <div class="text-lg font-semibold">{{ $it->properti?->nama ?? '-' }}</div>
        <div class="mt-2 text-gray-700 text-sm">Mulai: {{ $it->tgl_mulai_sewa }}</div>
        <div class="text-gray-700 text-sm">Akhir: {{ $it->tgl_akhir_sewa }}</div>
        <div class="mt-4">
          <a class="inline-block bg-blue-600 text-white px-3 py-2 rounded" href="{{ route('admin.kontrak.show',$it->id_kontrak) }}">Detail</a>
        </div>
      </div>
    @empty
      <div class="col-span-full text-center text-gray-500">Belum ada data.</div>
    @endforelse
  </div>
  <div class="mt-6">{{ $items->links() }}</div>
</div>
@endsection