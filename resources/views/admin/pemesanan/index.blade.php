@extends('layouts.app')
@section('content')
<div class="container mx-auto p-6">
  <h1 class="text-xl font-semibold mb-6">Daftar Pemesanan</h1>
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($items as $it)
      <div class="bg-white rounded-lg shadow p-4">
        <div class="flex items-start justify-between">
          <div>
            <div class="text-sm text-gray-600">ID: {{ $it->id_pemesanan }}</div>
            <div class="text-lg font-semibold">{{ $it->properti?->nama ?? '-' }}</div>
            <div class="text-gray-700">{{ $it->akun?->nama ?? '-' }}</div>
          </div>
          <div class="text-right">
            <span class="text-xs px-2 py-1 rounded bg-blue-100 text-blue-700">{{ ucfirst($it->status_pemesanan) }}</span>
            <div class="mt-1 text-xs px-2 py-1 rounded {{ $it->status_pembayaran==='lunas' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">{{ ucfirst($it->status_pembayaran) }}</div>
          </div>
        </div>
        <div class="mt-4">
          <a href="{{ route('admin.pemesanan.edit',$it->id_pemesanan) }}" class="inline-block bg-blue-600 text-white px-3 py-2 rounded">Edit</a>
        </div>
      </div>
    @empty
      <div class="col-span-full text-center text-gray-500">Belum ada data.</div>
    @endforelse
  </div>
  <div class="mt-6">{{ $items->links() }}</div>
</div>
@endsection