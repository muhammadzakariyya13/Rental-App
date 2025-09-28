<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Pemilik Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium mb-4">{{ __('Property Management Panel') }}</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-white dark:bg-gray-700 p-4 rounded-lg shadow">
                            <h4 class="font-medium mb-2">Property Management</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-300 mb-3">Manage your rental properties</p>
                            <a href="{{ route('pemilik.properti') }}" class="text-blue-600 dark:text-blue-400 hover:underline">Kelola Properti</a>
                        </div>
                          <div class="bg-white dark:bg-gray-700 p-4 rounded-lg shadow">
                            <h4 class="font-medium mb-2">Booking Management</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-300 mb-3">Manage tenant bookings</p>
                            <a href="{{ route('pemilik.pemesanan') }}" class="text-blue-600 dark:text-blue-400 hover:underline">Pemesanan</a>
                        </div>
                        
                        <div class="bg-white dark:bg-gray-700 p-4 rounded-lg shadow">
                            <h4 class="font-medium mb-2">Income Reports</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-300 mb-3">View your income reports</p>
                            <a href="#" class="text-blue-600 dark:text-blue-400 hover:underline">Laporan Pendapatan</a>
                        </div>
                    </div>
                    
                    <div class="mt-6">
                        <h3 class="text-lg font-medium mb-4">{{ __('Property Statistics') }}</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="bg-blue-100 dark:bg-blue-800 p-4 rounded-lg shadow">
                                <h4 class="font-medium mb-2">My Properties</h4>
                                <p class="text-3xl font-bold">3</p>
                                <a href="{{ route('pemilik.properti') }}" class="text-blue-600 dark:text-blue-400 text-sm hover:underline">Manage Properties</a>
                            </div>
                            
                            <div class="bg-yellow-100 dark:bg-yellow-800 p-4 rounded-lg shadow">
                                <h4 class="font-medium mb-2">Active Bookings</h4>
                                <p class="text-3xl font-bold">5</p>
                                <a href="{{ route('pemilik.pemesanan') }}" class="text-blue-600 dark:text-blue-400 text-sm hover:underline">View Bookings</a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-6">
                        <h3 class="text-lg font-medium mb-4">{{ __('Recent Activity') }}</h3>
                        <div class="bg-white dark:bg-gray-700 p-4 rounded-lg shadow">
                            <p class="text-sm text-gray-600 dark:text-gray-300">No recent activity to show.</p>
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
                        <h3>Dashboard Pemilik Properti</h3>
                    </div>
                    <div class="card-body">
                        <h5>Selamat datang, {{ Auth::user()->username }}!</h5>
                        <p>Dari dashboard ini Anda dapat mengelola properti dan pemesanan.</p>
                        
                        <div class="row mt-4">
                            <div class="col-md-6">
                                <div class="card bg-info text-white">
                                    <div class="card-body">
                                        <h5 class="card-title">Properti Saya</h5>
                                        <p class="card-text display-4">3</p>
                                        <a href="{{ route('pemilik.properti') }}" class="btn btn-light">Kelola Properti</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card bg-warning text-white">
                                    <div class="card-body">
                                        <h5 class="card-title">Pemesanan</h5>
                                        <p class="card-text display-4">5</p>
                                        <a href="{{ route('pemilik.pemesanan') }}" class="btn btn-light">Lihat Pemesanan</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <h4>Pemesanan Terbaru</h4>
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Penyewa</th>
                                        <th>Properti</th>
                                        <th>Tanggal</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Penyewa 1</td>
                                        <td>Rumah Mewah</td>
                                        <td>28 Sep 2025</td>
                                        <td><span class="badge bg-warning">Pending</span></td>
                                        <td>
                                            <button class="btn btn-sm btn-success">Konfirmasi</button>
                                            <button class="btn btn-sm btn-danger">Tolak</button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Penyewa 2</td>
                                        <td>Apartemen Modern</td>
                                        <td>26 Sep 2025</td>
                                        <td><span class="badge bg-success">Confirmed</span></td>
                                        <td>
                                            <button class="btn btn-sm btn-info">Detail</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
