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
        try {
            // Check if we have accounts and bookings
            $akuns = Akun::all();
            $pemesanans = Pemesanan::all();
            
            if ($akuns->isEmpty()) {
                $this->command->error('No accounts found. Please run AkunSeeder first.');
                return;
            }
            
            if ($pemesanans->isEmpty()) {
                $this->command->error('No bookings found. Please run PemesananSeeder first.');
                return;
            }
            
            // Create associations - using 'id' not 'id_akun' for the akun table
            AkunPemesanan::create([
                'id_akun' => $akuns->first()->id, // Changed from id_akun to id
                'id_pemesanan' => $pemesanans->first()->id_pemesanan,
            ]);
            
            // Add more associations if we have more data
            if ($akuns->count() > 1 && $pemesanans->count() > 1) {
                AkunPemesanan::create([
                    'id_akun' => $akuns[1]->id, // Changed from id_akun to id
                    'id_pemesanan' => $pemesanans[1]->id_pemesanan,
                ]);
            }
            
        } catch (\Exception $e) {
            $this->command->error('Failed to seed akun_pemesanan table: ' . $e->getMessage());
        }
    }
}