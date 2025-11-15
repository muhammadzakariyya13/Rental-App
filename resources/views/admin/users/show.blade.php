<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Detail User') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-medium mb-2">Username</label>
                            <p class="text-lg">{{ $user->username }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2">Email</label>
                            <p class="text-lg">{{ $user->email }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2">Status</label>
                            <span class="px-3 py-1 rounded text-sm {{ $user->status == 'aktif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ ucfirst($user->status ?? 'aktif') }}
                            </span>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2">Role</label>
                            <div class="flex gap-2">
                                @foreach($user->roles as $role)
                                    <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded text-sm">{{ ucfirst($role->name) }}</span>
                                @endforeach
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2">Tanggal Dibuat</label>
                            <p class="text-lg">{{ $user->created_at->format('d M Y H:i') }}</p>
                        </div>

                        <div class="flex gap-3">
                            <a href="{{ route('admin.users.edit', $user->id) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded transition">
                                Edit
                            </a>
                            <a href="{{ route('admin.users.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded transition">
                                Kembali
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
