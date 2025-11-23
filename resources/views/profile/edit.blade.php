@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto p-6">
    <h1 class="text-2xl font-semibold mb-4">Edit Profil</h1>

    @if(session('success'))
        <div class="bg-green-100 text-green-800 p-3 rounded mb-4">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="bg-red-100 text-red-800 p-3 rounded mb-4">
            <ul class="list-disc pl-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('profile.update') }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm font-medium text-gray-700">Nama</label>
            <input name="nama" value="{{ old('nama', $user->nama) }}" class="mt-1 block w-full border rounded px-3 py-2" required />
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Username</label>
            <input name="username" value="{{ old('username', $user->username) }}" class="mt-1 block w-full border rounded px-3 py-2" />
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Email</label>
            <input name="email" type="email" value="{{ old('email', $user->email) }}" class="mt-1 block w-full border rounded px-3 py-2" required />
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">No. Telp</label>
            <input name="phone_number" value="{{ old('phone_number', $user->phone_number) }}" class="mt-1 block w-full border rounded px-3 py-2" />
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Password baru (opsional)</label>
            <input name="password" type="password" class="mt-1 block w-full border rounded px-3 py-2" />
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Konfirmasi password</label>
            <input name="password_confirmation" type="password" class="mt-1 block w-full border rounded px-3 py-2" />
        </div>

        <div class="pt-4">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Simpan Perubahan</button>
            <a href="{{ route('dashboard') }}" class="ml-3 text-gray-600">Batal</a>
        </div>
    </form>
</div>
@endsection
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
