<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AccountPemesananSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan tabel account dan pemesanan sudah memiliki data
        // Sesuaikan id_akun dan id_pemesanan dengan data yang ada di database
        
        $account_pemesanans = [
            [
                'id_akun' => 1,
                'id_pemesanan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_akun' => 1,
                'id_pemesanan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_akun' => 2,
                'id_pemesanan' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('account_pemesanan')->insert($account_pemesanans);
    }
}