<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Call seeders in the correct order (respecting foreign key dependencies)
        $this->call([
            // Users/accounts first
            AkunSeeder::class,
            
            // Independent entities
            PropertiSeeder::class,
            
            // Entities with foreign keys
            PemesananSeeder::class,
            KontrakSeeder::class,
            
            // Pivot tables and relationships
            AkunPemesananSeeder::class,
            
            // Reviews depend on users and properties
            AkunReviewSeeder::class,
            ReviewSeeder::class
        ]);
    }
}