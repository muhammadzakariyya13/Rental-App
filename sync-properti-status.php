<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Properti;
use App\Models\Pemesanan;

echo "=== Sync Status Properti dengan Database ===\n\n";

// Get all properties
$properties = Properti::all();

foreach ($properties as $property) {
    echo "Properti: {$property->nama} (ID: {$property->id_properti})\n";
    echo "  Status saat ini: {$property->status}\n";
    
    // Check if there are any confirmed and paid bookings
    $hasActiveBooking = Pemesanan::where('id_properti', $property->id_properti)
        ->where('status_pemesanan', 'confirmed')
        ->where('status_pembayaran', 'sudah_bayar')
        ->exists();
    
    $correctStatus = $hasActiveBooking ? 'disewa' : 'tersedia';
    echo "  Status seharusnya: {$correctStatus}\n";
    
    if ($property->status !== $correctStatus) {
        $property->update(['status' => $correctStatus]);
        echo "  ✅ Status diupdate ke: {$correctStatus}\n";
    } else {
        echo "  ✓ Status sudah benar\n";
    }
    
    echo "\n";
}

echo "=== Selesai ===\n";
