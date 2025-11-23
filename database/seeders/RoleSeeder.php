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
        // Bersihkan role yang tidak diperlukan bila ada (opsional untuk migrasi non-fresh)
        \App\Models\Role::query()->whereNotIn('nama', ['admin','user'])->delete();

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
                'nama' => 'user',
                'nama_tampilan' => 'Pengguna',
                'deskripsi' => 'Pengguna aplikasi',
                'aktif' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}