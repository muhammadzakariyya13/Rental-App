<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Akun;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buat user admin
        $admin = Akun::create([
            'username' => 'admin',
            'email' => 'admin@rental.com',
            'password' => Hash::make('password'),
            'phone_number' => '081234567890',
        ]);
        
        // Buat user pemilik properti
        $pemilik = Akun::create([
            'username' => 'pemilik',
            'email' => 'pemilik@rental.com',
            'password' => Hash::make('password'),
            'phone_number' => '081234567891',
        ]);
        
        // Buat user penyewa
        $penyewa = Akun::create([
            'username' => 'penyewa',
            'email' => 'penyewa@rental.com',
            'password' => Hash::make('password'),
            'phone_number' => '081234567892',
        ]);
        
        // Tambahkan role ke user
        $adminRole = Role::where('name', 'admin')->first();
        $pemilikRole = Role::where('name', 'pemilik')->first();
        $penyewaRole = Role::where('name', 'penyewa')->first();
        
        $admin->roles()->attach($adminRole);
        $pemilik->roles()->attach($pemilikRole);
        $penyewa->roles()->attach($penyewaRole);
    }
}
