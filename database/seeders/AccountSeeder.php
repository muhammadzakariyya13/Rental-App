<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $accounts = [
            [
                'phone_number' => '081234567890',
                'email' => 'user1@example.com',
                'username' => 'user1',
                'password' => Hash::make('password123'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'phone_number' => '081234567891',
                'email' => 'user2@example.com',
                'username' => 'user2',
                'password' => Hash::make('password123'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'phone_number' => '081234567892',
                'email' => 'user3@example.com',
                'username' => 'user3',
                'password' => Hash::make('password123'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('akun')->insert($accounts);
    }
}