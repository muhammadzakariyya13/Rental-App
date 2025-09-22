<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pemesanan;
use App\Models\Akun;
use App\Models\Properti;

class PemesananSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get akun and properti data
        $akuns = Akun::all();
        $propertis = Properti::all();
        
        // Make sure we have akun and properti data
        if ($akuns->isEmpty() || $propertis->isEmpty()) {
            $this->command->error('Please run AkunSeeder and PropertiSeeder first.');
            return;
        }

        // Create sample pemesanan data
        Pemesanan::create([
            'id_akun' => $akuns->first()->id,
            'id_properti' => $propertis->first()->id_properti,
            'tanggal_pemesanan' => now(),
            'lama_sewa' => 30, // 30 days
            'status_pemesanan' => 'confirmed',
            'metode_pembayaran' => 'transfer bank',
            'status_pembayaran' => 'sudah_bayar',
        ]);
        
        // Add a second booking if we have more users and properties
        if ($akuns->count() > 1 && $propertis->count() > 1) {
            Pemesanan::create([
                'id_akun' => $akuns[1]->id,
                'id_properti' => $propertis[1]->id_properti,
                'tanggal_pemesanan' => now()->subDays(5),
                'lama_sewa' => 14, // 14 days
                'status_pemesanan' => 'pending',
                'metode_pembayaran' => 'e-wallet',
                'status_pembayaran' => 'belum_bayar',
            ]);
        }
    }
}