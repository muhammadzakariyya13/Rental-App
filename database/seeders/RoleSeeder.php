<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run()
    {
        $roles = [
            [
                'name' => 'admin',
                'display_name' => 'Administrator',
                'description' => 'Full access to all features'
            ],
            [
                'name' => 'pemilik',
                'display_name' => 'Pemilik Properti',
                'description' => 'Can manage their properties'
            ],
            [
                'name' => 'penyewa',
                'display_name' => 'Penyewa',
                'description' => 'Can rent properties'
            ]
        ];

        foreach ($roles as $role) {
            // GANTI dari create() menjadi updateOrCreate()
            Role::updateOrCreate(
                ['name' => $role['name']], // Kondisi pencarian
                $role // Data yang akan diupdate/create
            );
        }
    }
}