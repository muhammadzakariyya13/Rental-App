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
        // Jalankan Role dan Permission seeder terlebih dahulu
        $this->call([
            RoleSeeder::class,
            PermissionSeeder::class,
            RolePermissionSeeder::class,
        ]);
        
        // Jalankan User seeder dengan role
        $this->call([
            UserRoleSeeder::class,
        ]);

        // Seeder yang lain dapat dijalankan juga
        $this->call([
            // AccountSeeder::class,
            // PemesananSeeder::class,
            // AccountPemesananSeeder::class,
            // KontrakSeeder::class,
            // ReviewSeeder::class,
        ]);
        
        // Uncomment bagian-bagian ini jika diperlukan
        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        
        // User::factory(5)->create();
    }
}