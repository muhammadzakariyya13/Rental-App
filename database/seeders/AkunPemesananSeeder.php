<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AkunPemesanan;
use App\Models\Akun;
use App\Models\Pemesanan;

class AkunPemesananSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Make sure Akun and Pemesanan tables have data
        $akuns = Akun::all();
        $pemesanans = Pemesanan::all();
        
        // Check if both tables have data
        if ($akuns->count() > 0 && $pemesanans->count() > 0) {
            // Create associations between users and bookings
            AkunPemesanan::create([
                'id_akun' => $akuns->first()->id_akun,
                'id_pemesanan' => $pemesanans->first()->id_pemesanan
            ]);
            
            // Create more associations if more records exist
            if ($akuns->count() > 1 && $pemesanans->count() > 1) {
                AkunPemesanan::create([
                    'id_akun' => $akuns[1]->id_akun,
                    'id_pemesanan' => $pemesanans[1]->id_pemesanan
                ]);
            }
        } else {
            echo "Please run AkunSeeder and PemesananSeeder before running AccountPemesananSeeder.\n";
        }
    }
}