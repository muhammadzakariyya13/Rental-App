<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

echo "Migrating gambar properti to storage...\n";

// Buat folder jika belum ada
if (!file_exists(public_path('storage/properti'))) {
    mkdir(public_path('storage/properti'), 0755, true);
}

$properti = DB::table('properti')->whereNotNull('gambar')->get();

echo "Found " . $properti->count() . " properti\n";

foreach ($properti as $item) {
    $gambar = $item->gambar;
    
    // Skip jika sudah file path
    if (strpos($gambar, 'storage/properti/') === 0) {
        echo "Skip (already file): {$item->nama}\n";
        continue;
    }
    
    // Extract base64 data
    if (strpos($gambar, 'data:image') === 0) {
        preg_match('/data:image\/(\w+);base64,(.*)/', $gambar, $matches);
        if (count($matches) >= 3) {
            $extension = $matches[1];
            $data = base64_decode($matches[2]);
        } else {
            echo "Error parsing: {$item->nama}\n";
            continue;
        }
    } else {
        // Assume pure base64 JPEG
        $extension = 'jpeg';
        $data = base64_decode($gambar);
    }
    
    // Generate filename
    $filename = 'properti_' . $item->id_properti . '_' . time() . '.' . $extension;
    $path = public_path('storage/properti/' . $filename);
    
    // Save file
    file_put_contents($path, $data);
    
    // Update database
    DB::table('properti')
        ->where('id_properti', $item->id_properti)
        ->update([
            'gambar' => 'storage/properti/' . $filename
        ]);
    
    echo "Migrated: {$item->nama} -> {$filename}\n";
}

echo "Done!\n";
