@extends('layouts.app')
@section('content')
<div class="container mx-auto p-6">
  <h1 class="text-xl font-semibold mb-6">Daftar Akun</h1>
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($items as $it)
      <div class="bg-white rounded-lg shadow p-4">
        <div class="text-sm text-gray-600">ID: {{ $it->id }}</div>
        <div class="text-lg font-semibold">{{ $it->nama }}</div>
        <div class="text-gray-700">{{ $it->email }}</div>
        <div class="text-gray-600 text-sm">@{{ $it->username }}</div>
        <div class="mt-2 text-xs inline-block px-2 py-1 rounded bg-purple-100 text-purple-700">{{ $it->role?->nama ?? '-' }}</div>
        <div class="mt-4 flex gap-2">
          <a href="{{ route('admin.akun.edit',$it->id) }}" class="inline-block bg-blue-600 text-white px-3 py-2 rounded">Edit</a>
          @if(auth()->id() !== $it->id)
          <form method="POST" action="{{ route('admin.akun.destroy',$it->id) }}" onsubmit="return confirm('Hapus akun ini?')">
            @csrf
            @method('DELETE')
            <button class="bg-red-600 text-white px-3 py-2 rounded">Hapus</button>
          </form>
          @endif
        </div>
      </div>
    @empty
      <div class="col-span-full text-center text-gray-500">Belum ada data.</div>
    @endforelse
  </div>
  <div class="mt-6">{{ $items->links() }}</div>
</div>
@endsection