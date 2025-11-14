<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            [
                'name' => 'manage-users',
                'display_name' => 'Kelola Pengguna',
                'description' => 'Dapat menambah, mengedit, dan menghapus pengguna'
            ],
            [
                'name' => 'manage-properties',
                'display_name' => 'Kelola Properti',
                'description' => 'Dapat mengelola properti rental'
            ],
            [
                'name' => 'manage-bookings',
                'display_name' => 'Kelola Pemesanan',
                'description' => 'Dapat mengelola pemesanan'
            ],
            [
                'name' => 'view-reports',
                'display_name' => 'Lihat Laporan',
                'description' => 'Dapat melihat laporan dan statistik'
            ]
        ];

        foreach ($permissions as $permission) {
            // GANTI dari create() menjadi updateOrCreate()
            Permission::updateOrCreate(
                ['name' => $permission['name']], // Kondisi pencarian
                $permission // Data yang akan diupdate/create
            );
        }
    }
}