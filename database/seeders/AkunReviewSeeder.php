<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class AkunreviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil semua ID yang ada dari tabel properti dan akun
        $propertiIds = DB::table('properti')->pluck('id_properti');
        $akunIds = DB::table('akun')->pluck('id_akun');

        // Hentikan seeder jika tabel properti atau akun masih kosong
        if ($propertiIds->isEmpty() || $akunIds->isEmpty()) {
            $this->command->info('Tabel `properti` atau `akun` masih kosong. Harap jalankan seeder untuk tabel tersebut terlebih dahulu.');
            return;
        }

        $faker = Faker::create('id_ID');

        // Buat 50 data review dummy
        for ($i = 0; $i < 50; $i++) {
            DB::table('akunreview')->insert([
                'id_properti' => $faker->randomElement($propertiIds),
                'id_akun' => $faker->randomElement($akunIds),
                'rating' => $faker->randomFloat(1, 3, 5), // Angka float acak antara 3.0 s/d 5.0
                'komentar' => $faker->paragraph(2), // Komentar sepanjang 2 kalimat
                'tanggal' => $faker->dateTimeBetween('-1 year', 'now'), // Tanggal acak dalam setahun terakhir
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}