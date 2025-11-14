<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Akun;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserRoleSeeder extends Seeder
{
    public function run(): void
    {
        // Buat user admin
        $admin = Akun::firstOrCreate(['email' => 'admin@rental.com'], [
            'username' => 'admin',
            'password' => Hash::make('password'),
            'phone_number' => '081234567890',
        ]);
        
        // Buat user pemilik properti
        $pemilik = Akun::firstOrCreate(['email' => 'pemilik@rental.com'], [
            'username' => 'pemilik',
            'password' => Hash::make('password'),
            'phone_number' => '081234567891',
        ]);
        
        // Buat user penyewa
        $penyewa = Akun::firstOrCreate(['email' => 'penyewa@rental.com'], [
            'username' => 'penyewa',
            'password' => Hash::make('password'),
            'phone_number' => '081234567892',
        ]);
        
        // Tambahkan role ke user (cek dulu supaya tidak duplikat)
        $adminRole = Role::where('name', 'admin')->first();
        $pemilikRole = Role::where('name', 'pemilik')->first();
        $penyewaRole = Role::where('name', 'penyewa')->first();
        
        if ($adminRole && !$admin->roles()->where('role_id', $adminRole->id)->exists()) {
            $admin->roles()->attach($adminRole);
        }
        if ($pemilikRole && !$pemilik->roles()->where('role_id', $pemilikRole->id)->exists()) {
            $pemilik->roles()->attach($pemilikRole);
        }
        if ($penyewaRole && !$penyewa->roles()->where('role_id', $penyewaRole->id)->exists()) {
            $penyewa->roles()->attach($penyewaRole);
        }
    }
}