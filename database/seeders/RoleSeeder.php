<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::insert([
            [
                'nama' => 'admin',
                'nama_tampilan' => 'Administrator',
                'deskripsi' => 'Pengelola sistem',
                'aktif' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'officer',
                'nama_tampilan' => 'Petugas',
                'deskripsi' => 'Petugas operasional',
                'aktif' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'customer',
                'nama_tampilan' => 'Pelanggan',
                'deskripsi' => 'Pengguna aplikasi',
                'aktif' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}