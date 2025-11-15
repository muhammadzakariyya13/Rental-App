<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Kelola Pengguna') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if ($message = Session::get('success'))
                        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                            {{ $message }}
                        </div>
                    @endif

                    <!-- Tombol Tambah & Search -->
                    <div class="mb-6 flex gap-2 items-center">
                        <a href="{{ route('admin.users.create') }}" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded transition">
                            <i class="fas fa-plus mr-2"></i>Tambah User
                        </a>
                        <form method="GET" action="{{ route('admin.users.index') }}" class="flex-1 flex gap-2">
                            <input type="text" name="search" placeholder="Cari pengguna..." class="flex-1 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded dark:bg-gray-700 dark:text-white">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded transition">
                                Cari
                            </button>
                        </form>
                    </div>

                    <!-- Table -->
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead class="bg-gray-100 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3">ID</th>
                                    <th class="px-6 py-3">Username</th>
                                    <th class="px-6 py-3">Email</th>
                                    <th class="px-6 py-3">Phone</th>
                                    <th class="px-6 py-3">Role</th>
                                    <th class="px-6 py-3">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($users as $user)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="px-6 py-4">{{ $user->id }}</td>
                                        <td class="px-6 py-4">{{ $user->username }}</td>
                                        <td class="px-6 py-4">{{ $user->email }}</td>
                                        <td class="px-6 py-4">{{ $user->phone_number ?? '-' }}</td>
                                        <td class="px-6 py-4">
                                            @foreach($user->roles as $role)
                                                <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs">{{ ucfirst($role->name) }}</span>
                                            @endforeach
                                        </td>
                                        <td class="px-6 py-4 space-x-2">
                                            <a href="{{ route('admin.users.edit', $user->id) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded text-xs inline-block">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline" onclick="return confirm('Yakin ingin menghapus?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs">
                                                    <i class="fas fa-trash"></i> Hapus
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">Tidak ada data pengguna</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
