<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::insert([
            [
                'nama' => 'view_user',
                'nama_tampilan' => 'Lihat User',
                'deskripsi' => 'Melihat daftar user',
                'kelompok' => 'user',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'create_user',
                'nama_tampilan' => 'Tambah User',
                'deskripsi' => 'Menambah user baru',
                'kelompok' => 'user',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'edit_user',
                'nama_tampilan' => 'Edit User',
                'deskripsi' => 'Mengedit data user',
                'kelompok' => 'user',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'delete_user',
                'nama_tampilan' => 'Hapus User',
                'deskripsi' => 'Menghapus user',
                'kelompok' => 'user',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'view_property',
                'nama_tampilan' => 'Lihat Properti',
                'deskripsi' => 'Melihat daftar properti',
                'kelompok' => 'properti',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'create_property',
                'nama_tampilan' => 'Tambah Properti',
                'deskripsi' => 'Menambah properti baru',
                'kelompok' => 'properti',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Tambahkan permission lain sesuai kebutuhan...
        ]);
    }
}