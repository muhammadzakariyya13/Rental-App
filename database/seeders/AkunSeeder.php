<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Akun;
use Illuminate\Support\Facades\Hash;

class AkunSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sample user accounts
        Akun::create([
            'phone_number' => '081234567890',
            'email' => 'john@example.com',
            'username' => 'johndoe',
            'password' => Hash::make('password123'),
        ]);
        
        Akun::create([
            'phone_number' => '089876543210',
            'email' => 'jane@example.com',
            'username' => 'janesmith',
            'password' => Hash::make('password123'),
        ]);
    }
}