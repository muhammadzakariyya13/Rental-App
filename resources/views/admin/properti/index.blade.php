@extends('layouts.app')
@section('content')
<div class="container mx-auto p-6">
  <div class="flex items-center justify-between mb-4">
    <h1 class="text-xl font-semibold">Daftar Properti</h1>
    <a href="{{ route('admin.properti.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded">Tambah</a>
  </div>
  <div class="overflow-x-auto">
    <table class="min-w-full border">
      <thead>
        <tr class="bg-gray-100">
          <th class="p-2 border">ID</th>
          <th class="p-2 border">Nama</th>
          <th class="p-2 border">Harga</th>
          <th class="p-2 border">Status</th>
          <th class="p-2 border">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @foreach($items as $it)
        <tr>
          <td class="p-2 border">{{ $it->id_properti }}</td>
          <td class="p-2 border">{{ $it->nama }}</td>
          <td class="p-2 border">{{ $it->harga }}</td>
          <td class="p-2 border">{{ $it->status }}</td>
          <td class="p-2 border">
            <a href="{{ route('admin.properti.edit',$it->id_properti) }}" class="text-blue-600">Edit</a>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
  <div class="mt-4">{{ $items->links() }}</div>
</div>
@endsection