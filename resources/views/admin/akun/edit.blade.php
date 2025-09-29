@extends('layouts.app')
@section('content')
<div class="max-w-xl mx-auto p-6">
  <h1 class="text-xl font-semibold mb-4">Edit Akun #{{ $item->id }}</h1>
  <form method="POST" action="{{ route('admin.akun.update', $item->id) }}" class="space-y-4">
    @csrf
    @method('PUT')
    <div>
      <label>Nama</label>
      <input name="nama" class="border rounded w-full p-2" value="{{ old('nama',$item->nama) }}" required>
      @error('nama')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
    </div>
    <div>
      <label>Phone</label>
      <input name="phone_number" class="border rounded w-full p-2" value="{{ old('phone_number',$item->phone_number) }}" required>
      @error('phone_number')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
    </div>
    <div>
      <label>Email</label>
      <input name="email" type="email" class="border rounded w-full p-2" value="{{ old('email',$item->email) }}" required>
      @error('email')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
    </div>
    <div>
      <label>Username</label>
      <input name="username" class="border rounded w-full p-2" value="{{ old('username',$item->username) }}" required>
      @error('username')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
    </div>
    <div>
      <label>Role</label>
      <select name="role_id" class="border rounded w-full p-2">
        <option value="">-</option>
        @foreach($roles as $r)
          <option value="{{ $r->id }}" @selected(old('role_id',$item->role_id)===$r->id)>{{ $r->nama }}</option>
        @endforeach
      </select>
      @error('role_id')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
    </div>
    <button class="bg-blue-600 text-white px-4 py-2 rounded">Simpan</button>
  </form>
</div>
@endsection