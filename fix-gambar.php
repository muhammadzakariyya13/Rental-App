<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "Updating gambar properti...\n";

$properti = DB::table('properti')
    ->whereNotNull('gambar')
    ->where('gambar', 'NOT LIKE', 'data:%')
    ->get();

echo "Found " . $properti->count() . " properti to update\n";

foreach ($properti as $item) {
    DB::table('properti')
        ->where('id_properti', $item->id_properti)
        ->update([
            'gambar' => 'data:image/jpeg;base64,' . $item->gambar
        ]);
    echo "Updated: {$item->nama}\n";
}

echo "Done!\n";
