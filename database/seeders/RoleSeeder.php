<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Role::create([
            'name' => 'admin',
            'display_name' => 'Administrator',
            'description' => 'Administrator dengan akses penuh'
        ]);

        \App\Models\Role::create([
            'name' => 'pemilik',
            'display_name' => 'Pemilik Properti',
            'description' => 'Pengguna yang memiliki dan menyewakan properti'
        ]);

        \App\Models\Role::create([
            'name' => 'penyewa',
            'display_name' => 'Penyewa',
            'description' => 'Pengguna yang menyewa properti'
        ]);
    }
}
