<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Permission untuk admin
        \App\Models\Permission::create([
            'name' => 'manage-users',
            'display_name' => 'Kelola Pengguna',
            'description' => 'Dapat menambah, mengedit, dan menghapus pengguna'
        ]);
        
        \App\Models\Permission::create([
            'name' => 'manage-roles',
            'display_name' => 'Kelola Role',
            'description' => 'Dapat menambah, mengedit, dan menghapus role'
        ]);
        
        \App\Models\Permission::create([
            'name' => 'view-dashboard',
            'display_name' => 'Lihat Dashboard',
            'description' => 'Dapat melihat dashboard admin'
        ]);
        
        // Permission untuk pemilik properti
        \App\Models\Permission::create([
            'name' => 'manage-properties',
            'display_name' => 'Kelola Properti',
            'description' => 'Dapat menambah, mengedit, dan menghapus properti'
        ]);
        
        \App\Models\Permission::create([
            'name' => 'view-bookings',
            'display_name' => 'Lihat Pemesanan',
            'description' => 'Dapat melihat pemesanan untuk propertinya'
        ]);
        
        \App\Models\Permission::create([
            'name' => 'confirm-bookings',
            'display_name' => 'Konfirmasi Pemesanan',
            'description' => 'Dapat mengkonfirmasi pemesanan'
        ]);
        
        // Permission untuk penyewa
        \App\Models\Permission::create([
            'name' => 'browse-properties',
            'display_name' => 'Jelajahi Properti',
            'description' => 'Dapat melihat daftar properti'
        ]);
        
        \App\Models\Permission::create([
            'name' => 'book-property',
            'display_name' => 'Pesan Properti',
            'description' => 'Dapat memesan properti'
        ]);
        
        \App\Models\Permission::create([
            'name' => 'write-review',
            'display_name' => 'Tulis Review',
            'description' => 'Dapat menulis review untuk properti'
        ]);
    }
}
