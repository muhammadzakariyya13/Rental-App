<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
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
        // Pastikan tabel propertis dan pemesanans memiliki data
        // Sebelum menjalankan seeder ini, pastikan seeder Properti dan Pemesanan sudah dijalankan.
        
        $properti = Properti::first();
        $pemesanan = Pemesanan::first();
        
        // Cek apakah data properti dan pemesanan ada
        if ($properti && $pemesanan) {
            Kontrak::create([
                'id_properti' => $properti->id,
                'id_pemesanan' => $pemesanan->id,
                'tgl_mulai_sewa' => '2025-01-01',
                'tgl_akhir_sewa' => '2025-06-30',
                'harga_sewa' => 5000000.00,
            ]);
            
            Kontrak::create([
                'id_properti' => 1, // Ganti dengan ID properti yang valid
                'id_pemesanan' => 1, // Ganti dengan ID pemesanan yang valid
                'tgl_mulai_sewa' => '2025-03-01',
                'tgl_akhir_sewa' => '2025-08-31',
                'harga_sewa' => 7500000.00,
            ]);
            
        } else {
            echo "Pastikan PropertiSeeder dan PemesananSeeder sudah dijalankan terlebih dahulu.\n";
        }
    }
}