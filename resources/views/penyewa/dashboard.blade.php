<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Penyewa Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium mb-4">{{ __('Tenant Control Panel') }}</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div class="bg-white dark:bg-gray-700 p-4 rounded-lg shadow">
                            <h4 class="font-medium mb-2">Browse Properties</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-300 mb-3">Browse available rental properties</p>
                            <a href="{{ route('penyewa.browse') }}" class="text-blue-600 dark:text-blue-400 hover:underline">Jelajahi Properti</a>
                        </div>
                        
                        <div class="bg-white dark:bg-gray-700 p-4 rounded-lg shadow">
                            <h4 class="font-medium mb-2">My Bookings</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-300 mb-3">Manage your property bookings</p>
                            <a href="{{ route('penyewa.pemesanan') }}" class="text-blue-600 dark:text-blue-400 hover:underline">Pemesanan Saya</a>
                        </div>
                          <div class="bg-white dark:bg-gray-700 p-4 rounded-lg shadow">
                            <h4 class="font-medium mb-2">My Reviews</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-300 mb-3">Manage your property reviews</p>
                            <a href="{{ route('penyewa.review') }}" class="text-blue-600 dark:text-blue-400 hover:underline">Reviews</a>
                        </div>
                        
                        <div class="bg-white dark:bg-gray-700 p-4 rounded-lg shadow">
                            <h4 class="font-medium mb-2">Payment History</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-300 mb-3">View your payment history</p>
                            <a href="#" class="text-blue-600 dark:text-blue-400 hover:underline">Riwayat Pembayaran</a>
                        </div>
                    </div>
                    
                    <div class="mt-6">
                        <h3 class="text-lg font-medium mb-4">{{ __('Booking Statistics') }}</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="bg-blue-100 dark:bg-blue-800 p-4 rounded-lg shadow">
                                <h4 class="font-medium mb-2">Available Properties</h4>
                                <p class="text-3xl font-bold">10</p>
                                <a href="{{ route('penyewa.browse') }}" class="text-blue-600 dark:text-blue-400 text-sm hover:underline">Browse Properties</a>
                            </div>
                            
                            <div class="bg-green-100 dark:bg-green-800 p-4 rounded-lg shadow">
                                <h4 class="font-medium mb-2">My Bookings</h4>
                                <p class="text-3xl font-bold">2</p>
                                <a href="{{ route('penyewa.pemesanan') }}" class="text-blue-600 dark:text-blue-400 text-sm hover:underline">View Bookings</a>
                            </div>
                            
                            <div class="bg-yellow-100 dark:bg-yellow-800 p-4 rounded-lg shadow">
                                <h4 class="font-medium mb-2">My Reviews</h4>
                                <p class="text-3xl font-bold">1</p>
                                <a href="{{ route('penyewa.review') }}" class="text-blue-600 dark:text-blue-400 text-sm hover:underline">View Reviews</a>
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
                    <li class="nav-item">
                        <form action="{{ route('logout') }}" method="POST">
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
                        <h3>Dashboard Penyewa</h3>
                    </div>
                    <div class="card-body">
                        <h5>Selamat datang, {{ Auth::user()->username }}!</h5>
                        <p>Dari dashboard ini Anda dapat menjelajahi properti dan mengelola pemesanan Anda.</p>
                        
                        <div class="row mt-4">
                            <div class="col-md-4">
                                <div class="card bg-primary text-white">
                                    <div class="card-body">
                                        <h5 class="card-title">Properti Tersedia</h5>
                                        <p class="card-text display-4">10</p>
                                        <a href="{{ route('penyewa.browse') }}" class="btn btn-light">Jelajahi</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card bg-success text-white">
                                    <div class="card-body">
                                        <h5 class="card-title">Pemesanan Aktif</h5>
                                        <p class="card-text display-4">2</p>
                                        <a href="{{ route('penyewa.pemesanan') }}" class="btn btn-light">Lihat Pemesanan</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card bg-warning text-white">
                                    <div class="card-body">
                                        <h5 class="card-title">Review Saya</h5>
                                        <p class="card-text display-4">3</p>
                                        <a href="{{ route('penyewa.review') }}" class="btn btn-light">Lihat Review</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <h4>Pemesanan Terbaru</h4>
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Properti</th>
                                        <th>Tanggal Pemesanan</th>
                                        <th>Lama Sewa</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Villa Mewah</td>
                                        <td>25 Sep 2025</td>
                                        <td>7 hari</td>
                                        <td><span class="badge bg-success">Confirmed</span></td>
                                        <td>
                                            <button class="btn btn-sm btn-info">Detail</button>
                                            <button class="btn btn-sm btn-warning">Tulis Review</button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Apartemen Modern</td>
                                        <td>20 Sep 2025</td>
                                        <td>30 hari</td>
                                        <td><span class="badge bg-warning">Pending</span></td>
                                        <td>
                                            <button class="btn btn-sm btn-info">Detail</button>
                                            <button class="btn btn-sm btn-danger">Batalkan</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="mt-4">
                            <h4>Rekomendasi Properti</h4>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="card">
                                        <img src="https://via.placeholder.com/350x200" class="card-img-top" alt="Properti">
                                        <div class="card-body">
                                            <h5 class="card-title">Villa Indah</h5>
                                            <p class="card-text">Villa mewah dengan pemandangan pegunungan</p>
                                            <p><strong>Rp 1.500.000 / hari</strong></p>
                                            <a href="#" class="btn btn-primary">Lihat Detail</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card">
                                        <img src="https://via.placeholder.com/350x200" class="card-img-top" alt="Properti">
                                        <div class="card-body">
                                            <h5 class="card-title">Rumah Minimalis</h5>
                                            <p class="card-text">Rumah modern dengan desain minimalis</p>
                                            <p><strong>Rp 800.000 / hari</strong></p>
                                            <a href="#" class="btn btn-primary">Lihat Detail</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card">
                                        <img src="https://via.placeholder.com/350x200" class="card-img-top" alt="Properti">
                                        <div class="card-body">
                                            <h5 class="card-title">Apartemen Mewah</h5>
                                            <p class="card-text">Apartemen di pusat kota dengan fasilitas lengkap</p>
                                            <p><strong>Rp 1.200.000 / hari</strong></p>
                                            <a href="#" class="btn btn-primary">Lihat Detail</a>
                                        </div>
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
