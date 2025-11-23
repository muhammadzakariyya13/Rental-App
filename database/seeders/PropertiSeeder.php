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

        // Tipe properti
        $tipeProperti = ['Rumah', 'Apartemen', 'Kost', 'Ruko', 'Vila', 'Townhouse'];
        
        // Loop untuk membuat data dummy
        for ($i = 1; $i <= 15; $i++) {
            $namaProperti = $tipeProperti[array_rand($tipeProperti)] . ' ' . $faker->streetName . ' ' . $i;
            
            DB::table('properti')->updateOrInsert(
                ['nama' => $namaProperti],
                [
                    'harga' => $faker->numberBetween(1000000, 15000000), // 1jt - 15jt per bulan
                    'deskripsi' => 'Lokasi: ' . $faker->address . '. ' . $faker->realText(100) . ' Dilengkapi dengan fasilitas lengkap dan lokasi strategis.',
                    'status' => $faker->randomElement(['tersedia', 'disewa']),
                    'foto' => null, // Bisa diisi nanti jika ada foto
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}