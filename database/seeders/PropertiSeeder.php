<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class PropertiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Inisialisasi Faker untuk data berbahasa Indonesia
        $faker = Faker::create('id_ID');

        // Loop untuk membuat 20 data dummy
        for ($i = 1; $i <= 20; $i++) {
            DB::table('properti')->insert([
                'nama' => 'Properti ' . $faker->company . ' ' . $faker->city,
                // Harga acak antara 500 juta hingga 3 milyar
                'harga' => $faker->numberBetween(500000000, 3000000000),
                'deskripsi' => $faker->realText(200),
                'status' => $faker->randomElement(['tersedia', 'disewa']),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}