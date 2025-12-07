@extends('layouts.penyewa')

@section('title', 'Edit Profile')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 md:px-8">
        
        {{-- Header --}}
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">👤 Profile Saya</h1>
            <p class="text-gray-600">Kelola informasi profil dan keamanan akun Anda</p>
        </div>

        {{-- Success/Error Messages --}}
        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg mb-6 flex items-center">
                <svg class="w-6 h-6 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('status') === 'profile-updated')
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg mb-6 flex items-center">
                <svg class="w-6 h-6 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <span>Profile berhasil diperbarui!</span>
            </div>
        @endif

        @if(session('status') === 'password-updated')
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg mb-6 flex items-center">
                <svg class="w-6 h-6 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <span>Password berhasil diperbarui!</span>
            </div>
        @endif

        {{-- Profile Information Card --}}
        <div class="bg-white rounded-xl shadow-md p-6 md:p-8 mb-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                <svg class="w-6 h-6 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                Informasi Profile
            </h2>

            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PATCH')

                {{-- Profile Photo --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Foto Profile</label>
                    <div class="flex items-center gap-6">
                        {{-- Photo Preview --}}
                        <div class="relative">
                            @if($user->profile_photo)
                                <img id="photo-preview" 
                                     src="{{ asset('storage/' . $user->profile_photo) }}" 
                                     alt="Profile Photo" 
                                     class="w-24 h-24 rounded-full object-cover border-4 border-gray-200">
                            @else
                                <div id="photo-preview" class="w-24 h-24 rounded-full bg-gradient-to-br from-sky-400 to-blue-500 flex items-center justify-center text-white text-3xl font-bold border-4 border-gray-200">
                                    {{ strtoupper(substr($user->username, 0, 1)) }}
                                </div>
                            @endif
                        </div>

                        {{-- Upload Controls --}}
                        <div class="flex-1">
                            <input type="file" 
                                   id="profile_photo" 
                                   name="profile_photo" 
                                   accept="image/*"
                                   class="hidden"
                                   onchange="previewPhoto(event)">
                            
                            <div class="flex gap-3">
                                <label for="profile_photo" 
                                       class="bg-sky-600 hover:bg-sky-700 text-white px-4 py-2 rounded-lg transition font-medium cursor-pointer inline-block text-center">
                                    📷 Pilih Foto
                                </label>
                                
                                @if($user->profile_photo)
                                    <button type="button"
                                            onclick="removePhoto()"
                                            class="bg-red-100 hover:bg-red-200 text-red-700 px-4 py-2 rounded-lg transition font-medium">
                                        🗑️ Hapus Foto
                                    </button>
                                @endif
                            </div>
                            
                            <p class="text-xs text-gray-500 mt-2">JPG, PNG atau GIF (Max. 2MB)</p>
                            @error('profile_photo')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Username --}}
                <div>
                    <label for="username" class="block text-sm font-semibold text-gray-700 mb-2">Username</label>
                    <input type="text" 
                           id="username" 
                           name="username" 
                           value="{{ old('username', $user->username) }}"
                           class="w-full px-4 py-3 rounded-lg border-2 focus:ring-2 focus:ring-sky-500 focus:border-transparent {{ $errors->has('username') ? 'border-red-500' : 'border-gray-300' }}"
                           required>
                    @error('username')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           value="{{ old('email', $user->email) }}"
                           class="w-full px-4 py-3 rounded-lg border-2 focus:ring-2 focus:ring-sky-500 focus:border-transparent {{ $errors->has('email') ? 'border-red-500' : 'border-gray-300' }}"
                           required>
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    
                    @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                        <div class="mt-2 text-sm">
                            <p class="text-gray-600">
                                Email Anda belum diverifikasi.
                                <button form="send-verification" class="underline text-sky-600 hover:text-sky-700 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500">
                                    Klik di sini untuk mengirim ulang email verifikasi.
                                </button>
                            </p>

                            @if (session('status') === 'verification-link-sent')
                                <p class="mt-2 font-medium text-sm text-green-600">
                                    Link verifikasi baru telah dikirim ke email Anda.
                                </p>
                            @endif
                        </div>
                    @endif
                </div>

                {{-- Phone Number --}}
                <div>
                    <label for="phone_number" class="block text-sm font-semibold text-gray-700 mb-2">Nomor Telepon</label>
                    <input type="text" 
                           id="phone_number" 
                           name="phone_number" 
                           value="{{ old('phone_number', $user->phone_number) }}"
                           placeholder="08123456789"
                           class="w-full px-4 py-3 rounded-lg border-2 focus:ring-2 focus:ring-sky-500 focus:border-transparent {{ $errors->has('phone_number') ? 'border-red-500' : 'border-gray-300' }}">
                    @error('phone_number')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Buttons --}}
                <div class="flex gap-3">
                    <button type="submit" 
                            class="bg-sky-600 hover:bg-sky-700 text-white px-6 py-3 rounded-lg transition font-medium shadow-sm">
                        💾 Simpan Perubahan
                    </button>
                    <a href="{{ route('penyewa.dashboard') }}" 
                       class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-3 rounded-lg transition font-medium">
                        Batal
                    </a>
                </div>
            </form>
        </div>

        {{-- Update Password Card --}}
        <div class="bg-white rounded-xl shadow-md p-6 md:p-8 mb-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-2 flex items-center gap-2">
                <svg class="w-6 h-6 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
                Ubah Password
            </h2>
            <p class="text-gray-600 mb-6">Pastikan akun Anda menggunakan password yang panjang dan acak untuk tetap aman.</p>

            <form method="POST" action="{{ route('password.update') }}" class="space-y-6">
                @csrf
                @method('PUT')

                {{-- Current Password --}}
                <div>
                    <label for="current_password" class="block text-sm font-semibold text-gray-700 mb-2">Password Saat Ini</label>
                    <input type="password" 
                           id="current_password" 
                           name="current_password" 
                           class="w-full px-4 py-3 rounded-lg border-2 focus:ring-2 focus:ring-sky-500 focus:border-transparent {{ $errors->updatePassword->has('current_password') ? 'border-red-500' : 'border-gray-300' }}"
                           autocomplete="current-password">
                    @error('current_password', 'updatePassword')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- New Password --}}
                <div>
                    <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">Password Baru</label>
                    <input type="password" 
                           id="password" 
                           name="password" 
                           class="w-full px-4 py-3 rounded-lg border-2 focus:ring-2 focus:ring-sky-500 focus:border-transparent {{ $errors->updatePassword->has('password') ? 'border-red-500' : 'border-gray-300' }}"
                           autocomplete="new-password">
                    @error('password', 'updatePassword')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Confirm Password --}}
                <div>
                    <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">Konfirmasi Password Baru</label>
                    <input type="password" 
                           id="password_confirmation" 
                           name="password_confirmation" 
                           class="w-full px-4 py-3 rounded-lg border-2 focus:ring-2 focus:ring-sky-500 focus:border-transparent {{ $errors->updatePassword->has('password_confirmation') ? 'border-red-500' : 'border-gray-300' }}"
                           autocomplete="new-password">
                    @error('password_confirmation', 'updatePassword')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Buttons --}}
                <div class="flex gap-3">
                    <button type="submit" 
                            class="bg-sky-600 hover:bg-sky-700 text-white px-6 py-3 rounded-lg transition font-medium shadow-sm">
                        🔒 Update Password
                    </button>
                </div>
            </form>
        </div>

        {{-- Delete Account Card --}}
        <div class="bg-red-50 border-2 border-red-200 rounded-xl p-6 md:p-8">
            <h2 class="text-2xl font-bold text-red-700 mb-2 flex items-center gap-2">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                Hapus Akun
            </h2>
            <p class="text-red-600 mb-6">Setelah akun Anda dihapus, semua data dan kontrak akan dihapus secara permanen. Sebelum menghapus akun, silakan unduh data atau informasi yang ingin Anda simpan.</p>

            <button type="button"
                    onclick="if(confirm('Apakah Anda yakin ingin menghapus akun? Tindakan ini tidak dapat dibatalkan!')) { document.getElementById('delete-account-form').submit(); }"
                    class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-lg transition font-medium shadow-sm">
                🗑️ Hapus Akun
            </button>

            <form id="delete-account-form" method="POST" action="{{ route('profile.destroy') }}" class="hidden">
                @csrf
                @method('DELETE')
            </form>
        </div>
    </div>
</div>

{{-- Verification Email Form --}}
@if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
<form id="send-verification" method="post" action="{{ route('verification.send') }}" style="display: none;">
    @csrf
</form>
@endif

<script>
function previewPhoto(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('photo-preview');
            preview.innerHTML = `<img src="${e.target.result}" alt="Preview" class="w-24 h-24 rounded-full object-cover border-4 border-gray-200">`;
        }
        reader.readAsDataURL(file);
    }
}

function removePhoto() {
    if (confirm('Apakah Anda yakin ingin menghapus foto profil?')) {
        const preview = document.getElementById('photo-preview');
        const username = '{{ $user->username }}';
        const initial = username.charAt(0).toUpperCase();
        preview.innerHTML = `<div class="w-24 h-24 rounded-full bg-gradient-to-br from-sky-400 to-blue-500 flex items-center justify-center text-white text-3xl font-bold border-4 border-gray-200">${initial}</div>`;
        
        // Create a hidden input to signal photo removal
        const form = document.querySelector('form[action="{{ route('profile.update') }}"]');
        let removeInput = form.querySelector('input[name="remove_photo"]');
        if (!removeInput) {
            removeInput = document.createElement('input');
            removeInput.type = 'hidden';
            removeInput.name = 'remove_photo';
            removeInput.value = '1';
            form.appendChild(removeInput);
        }
        
        // Clear file input
        document.getElementById('profile_photo').value = '';
    }
}
</script>
@endsection
