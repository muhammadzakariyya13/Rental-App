<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kontrak;
use App\Models\Properti;
use App\Models\Pemesanan;

class KontrakSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get property and booking data
        $properti = Properti::first();
        $pemesanan = Pemesanan::first();
        
        // Check if property and booking data exists
        if ($properti && $pemesanan) {
            Kontrak::create([
                'id_properti' => $properti->id_properti,
                'id_pemesanan' => $pemesanan->id_pemesanan,
                'tgl_mulai_sewa' => '2025-01-01',
                'tgl_akhir_sewa' => '2025-06-30',
                'harga_sewa' => 5000000.00,
            ]);
            
            // If we have more than one property and booking
            $secondProperti = Properti::skip(1)->first();
            $secondPemesanan = Pemesanan::skip(1)->first();
            
            if ($secondProperti && $secondPemesanan) {
                Kontrak::create([
                    'id_properti' => $secondProperti->id_properti,
                    'id_pemesanan' => $secondPemesanan->id_pemesanan,
                    'tgl_mulai_sewa' => '2025-02-01',
                    'tgl_akhir_sewa' => '2025-08-31',
                    'harga_sewa' => 7500000.00,
                ]);
            }
        } else {
            $this->command->error('Please run PropertiSeeder and PemesananSeeder first.');
        }
    }
}