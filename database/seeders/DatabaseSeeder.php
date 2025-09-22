<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\ReviewSeeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // User::factory(10)->create();
        // Panggil seeders dalam urutan yang benar
        $this->call([
            AccountSeeder::class,
            // PemesananSeeder::class, // Uncomment jika Anda juga membuat seeder untuk pemesanan
            AccountPemesananSeeder::class,
        ]);

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
        
        $this->call([
            KontrakSeeder::class,
        ]);

        // Create 5 users first
        User::factory(5)->create();
        
        // Run the ReviewSeeder
        $this->call([
            ReviewSeeder::class
        ]);
    }
}