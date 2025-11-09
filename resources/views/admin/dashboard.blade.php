<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium mb-4">{{ __('Admin Management Panel') }}</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-white dark:bg-gray-700 p-4 rounded-lg shadow">
                            <h4 class="font-medium mb-2">User Management</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-300 mb-3">Manage users, roles and permissions</p>
                            <a href="{{ route('admin.users.index') }}" class="text-blue-600 dark:text-blue-400 hover:underline">Kelola Pengguna</a>
                        </div>
                          <div class="bg-white dark:bg-gray-700 p-4 rounded-lg shadow">
                            <h4 class="font-medium mb-2">Role Management</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-300 mb-3">Manage system roles and permissions</p>
                            <a href="{{ route('admin.roles.index') }}" class="text-blue-600 dark:text-blue-400 hover:underline">Kelola Role</a>
                        </div>
                          <div class="bg-white dark:bg-gray-700 p-4 rounded-lg shadow">
                            <h4 class="font-medium mb-2">Permissions</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-300 mb-3">Manage permissions for roles</p>
                            <a href="{{ route('admin.roles.index') }}" class="text-blue-600 dark:text-blue-400 hover:underline">Kelola Permissions</a>
                        </div>
                    </div>
                    
                    <div class="mt-6">
                        <h3 class="text-lg font-medium mb-4">{{ __('System Statistics') }}</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="bg-blue-100 dark:bg-blue-800 p-4 rounded-lg shadow">
                                <h4 class="font-medium mb-2">Total Users</h4>
                                <p class="text-3xl font-bold">{{ App\Models\Akun::count() }}</p>
                            </div>
                            
                            <div class="bg-green-100 dark:bg-green-800 p-4 rounded-lg shadow">
                                <h4 class="font-medium mb-2">Total Roles</h4>
                                <p class="text-3xl font-bold">{{ App\Models\Role::count() }}</p>
                            </div>
                            
                            <div class="bg-yellow-100 dark:bg-yellow-800 p-4 rounded-lg shadow">
                                <h4 class="font-medium mb-2">Total Permissions</h4>
                                <p class="text-3xl font-bold">{{ App\Models\Permission::count() }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
                            @csrf
                            <button type="submit" class="btn btn-link nav-link">Logout</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3>Dashboard Admin</h3>
                    </div>
                    <div class="card-body">
                        <h5>Selamat datang, Admin!</h5>
                        <p>Dari dashboard ini Anda dapat mengelola seluruh sistem Rental App.</p>
                        
                        <div class="row mt-4">
                            <div class="col-md-4">                                <div class="card bg-info text-white">
                                    <div class="card-body">
                                        <h5 class="card-title">Pengguna</h5>
                                        <p class="card-text display-4">{{ App\Models\Akun::count() }}</p>
                                        <a href="{{ route('admin.users.index') }}" class="btn btn-light">Kelola Pengguna</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">                                <div class="card bg-success text-white">
                                    <div class="card-body">
                                        <h5 class="card-title">Role</h5>
                                        <p class="card-text display-4">{{ App\Models\Role::count() }}</p>
                                        <a href="{{ route('admin.roles.index') }}" class="btn btn-light">Kelola Role</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">                                <div class="card bg-warning text-white">
                                    <div class="card-body">
                                        <h5 class="card-title">Permissions</h5>
                                        <p class="card-text display-4">{{ App\Models\Permission::count() }}</p>
                                        <a href="{{ route('admin.roles.index') }}" class="btn btn-light">Kelola Permissions</a>
                                    </div>
                                </div>
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
