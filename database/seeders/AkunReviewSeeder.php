<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AkunReview;
use App\Models\Akun;
use App\Models\Properti;

class AkunReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try {
            // Get all users and properties
            $akuns = Akun::all();
            $propertis = Properti::all();
            
            // Check if we have users and properties
            if ($akuns->isEmpty()) {
                $this->command->error('No accounts found. Please run AkunSeeder first.');
                return;
            }
            
            if ($propertis->isEmpty()) {
                $this->command->error('No properties found. Please run PropertiSeeder first.');
                return;
            }
            
            // Create reviews
            AkunReview::create([
                'id_akun' => $akuns->first()->id, // Using 'id' not 'id_akun'
                'id_properti' => $propertis->first()->id_properti,
                'review_text' => 'This property was amazing! Great location and very clean.',
                'rating' => 5,
            ]);
            
            // Create more reviews if we have more users and properties
            if ($akuns->count() > 1 && $propertis->count() > 1) {
                AkunReview::create([
                    'id_akun' => $akuns[1]->id,
                    'id_properti' => $propertis[1]->id_properti,
                    'review_text' => 'Good property, but a bit noisy at night.',
                    'rating' => 4,
                ]);
            }
            
            // Add a second review from the first user
            if ($propertis->count() > 1) {
                AkunReview::create([
                    'id_akun' => $akuns->first()->id,
                    'id_properti' => $propertis->count() > 1 ? $propertis[1]->id_properti : $propertis->first()->id_properti,
                    'review_text' => 'I would definitely stay here again. The host was very helpful.',
                    'rating' => 5,
                ]);
            }
            
            $this->command->info('AkunReview data seeded successfully!');
            
        } catch (\Exception $e) {
            $this->command->error('Failed to seed akunreview table: ' . $e->getMessage());
        }
    }
}