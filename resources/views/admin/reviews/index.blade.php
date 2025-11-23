@extends('layouts.app')
@section('content')
<div class="container mx-auto p-6">
  <h1 class="text-xl font-semibold mb-6">Daftar Review</h1>
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($items as $it)
      <div class="bg-white rounded-lg shadow p-4 flex flex-col">
        <div class="flex items-start justify-between">
          <div>
            <div class="text-sm text-gray-600">ID: {{ $it->id }}</div>
            <div class="font-semibold">{{ $it->properti?->nama ?? '-' }}</div>
            <div class="text-gray-700 text-sm">{{ $it->akun?->nama ?? '-' }}</div>
          </div>
          <div class="text-yellow-500 font-semibold">★ {{ $it->rating }}</div>
        </div>
        <p class="mt-3 text-gray-700 line-clamp-4">{{ $it->komentar }}</p>
        <div class="mt-4">
          <form method="POST" action="{{ route('admin.reviews.destroy',$it->id) }}">
            @csrf
            @method('DELETE')
            <button class="bg-red-600 text-white px-3 py-2 rounded" onclick="return confirm('Hapus review ini?')">Hapus</button>
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