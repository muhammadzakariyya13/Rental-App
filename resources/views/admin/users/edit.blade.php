<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit User') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div>
                            <label for="username" class="block text-sm font-medium mb-2">Username</label>
                            <input type="text" id="username" name="username" value="{{ $user->username }}" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded">
                            @error('username')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium mb-2">Email</label>
                            <input type="email" id="email" name="email" value="{{ $user->email }}" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded">
                            @error('email')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="phone_number" class="block text-sm font-medium mb-2">Nomor Telepon</label>
                            <input type="text" id="phone_number" name="phone_number" value="{{ $user->phone_number }}" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded">
                            @error('phone_number')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="roles" class="block text-sm font-medium mb-2">Role</label>
                            <div class="space-y-2">
                                @foreach($roles as $role)
                                    <label class="flex items-center">
                                        <input type="checkbox" name="roles[]" value="{{ $role->id }}" class="rounded dark:bg-gray-700" {{ in_array($role->id, $userRoles) ? 'checked' : '' }}>
                                        <span class="ms-2 text-sm dark:text-gray-300">{{ ucfirst($role->name) }}</span>
                                    </label>
                                @endforeach
                            </div>
                            @error('roles')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex gap-3">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded transition">
                                Simpan
                            </button>
                            <a href="{{ route('admin.users.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded transition">
                                Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
