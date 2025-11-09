<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profil Saya') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium">Informasi Profil</h3>
                        <a href="{{ route('profile.edit') }}" class="px-4 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600">
                            Edit Profil
                        </a>
                    </div>

                    @if (session('success'))
                        <div class="mb-4 text-sm text-green-600">
                            {{ session('success') }}
                        </div>
                    @endif                    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                        <div class="border-t border-gray-200">
                            <dl>
                                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">Username</dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:col-span-2">{{ $user->username }}</dd>
                                </div>
                                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">Email</dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:col-span-2">{{ $user->email }}</dd>
                                </div>
                                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">Nomor Telepon</dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:col-span-2">{{ $user->phone_number }}</dd>
                                </div>
                                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">Role</dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:col-span-2">
                                        @foreach ($user->roles as $role)
                                            <span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">
                                                {{ ucfirst($role->name) }}
                                            </span>
                                        @endforeach
                                    </dd>                                </div>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>>
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3>Profil Saya</h3>
                        <a href="{{ route('profile.edit') }}" class="btn btn-warning">Edit Profil</a>
                    </div>
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif
                        
                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">Username:</div>
                            <div class="col-md-8">{{ $user->username }}</div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">Email:</div>
                            <div class="col-md-8">{{ $user->email }}</div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">Nomor Telepon:</div>
                            <div class="col-md-8">{{ $user->phone_number }}</div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">Role:</div>
                            <div class="col-md-8">
                                @foreach ($user->roles as $role)
                                    <span class="badge bg-info">{{ ucfirst($role->name) }}</span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
