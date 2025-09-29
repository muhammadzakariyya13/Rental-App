@extends('layouts.app')
@section('content')
<div class="container mx-auto p-6">
  <h1 class="text-xl font-semibold mb-4">Daftar Pemesanan</h1>
  <div class="overflow-x-auto">
    <table class="min-w-full border">
      <thead>
        <tr class="bg-gray-100">
          <th class="p-2 border">ID</th>
          <th class="p-2 border">User</th>
          <th class="p-2 border">Properti</th>
          <th class="p-2 border">Status</th>
          <th class="p-2 border">Pembayaran</th>
          <th class="p-2 border"></th>
        </tr>
      </thead>
      <tbody>
        @foreach($items as $it)
        <tr>
          <td class="p-2 border">{{ $it->id_pemesanan }}</td>
          <td class="p-2 border">{{ $it->akun?->nama }}</td>
          <td class="p-2 border">{{ $it->properti?->nama }}</td>
          <td class="p-2 border">{{ $it->status_pemesanan }}</td>
          <td class="p-2 border">{{ $it->status_pembayaran }}</td>
          <td class="p-2 border">
            <a href="{{ route('admin.pemesanan.edit',$it->id_pemesanan) }}" class="text-blue-600">Edit</a>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
  <div class="mt-4">{{ $items->links() }}</div>
</div>
@endsection