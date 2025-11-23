@extends('layouts.app')
@section('content')
<div class="container mx-auto p-6">
  <div class="flex items-center justify-between mb-6">
    <h1 class="text-xl font-semibold">Daftar Properti</h1>
    <a href="{{ route('admin.properti.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded">Tambah</a>
  </div>

  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($items as $it)
      <div class="bg-white rounded-lg shadow p-4 flex flex-col">
        @if($it->foto)
          <img src="{{ asset('storage/'.$it->foto) }}" alt="{{ $it->nama }}" class="w-full h-40 object-cover rounded-md mb-3">
        @endif
        <div class="flex-1">
          <div class="text-lg font-semibold mb-1">{{ $it->nama }}</div>
          <div class="text-gray-600 text-sm">ID: {{ $it->id_properti }}</div>
          <div class="mt-2 font-medium">Rp {{ number_format($it->harga,0,',','.') }}</div>
          <span class="inline-block mt-2 text-xs px-2 py-1 rounded {{ $it->status==='tersedia' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">{{ ucfirst($it->status) }}</span>
        </div>
        <div class="mt-4 flex gap-2">
          <a href="{{ route('admin.properti.edit',$it->id_properti) }}" class="inline-block bg-blue-600 text-white px-3 py-2 rounded">Edit</a>
          <form method="POST" action="{{ route('admin.properti.destroy',$it->id_properti) }}" onsubmit="return confirm('Hapus properti ini?')">
            @csrf
            @method('DELETE')
            <button class="bg-red-600 text-white px-3 py-2 rounded">Hapus</button>
          </form>
        </div>
      </div>
    @empty
      <div class="col-span-full text-center text-gray-500">Belum ada data.</div>
    @endforelse
  </div>

  <div class="mt-6">{{ $items->links() }}</div>
</div>
@endsection