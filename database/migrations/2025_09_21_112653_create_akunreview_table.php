<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('akunreview', function (Blueprint $table) {
            // Sesuai dengan `id_review` (BIGINT, UNSIGNED, AUTO_INCREMENT)
            $table->id('id_review');

            // Sesuai dengan `id_properti` dan `id_akun` (BIGINT, UNSIGNED)
            // Sekaligus membuat foreign key constraint
            $table->foreignId('id_properti')->constrained('properti');
            $table->foreignId('id_akun')->constrained('akun');

            // Sesuai dengan kolom `rating`
            $table->float('rating');

            // Sesuai dengan kolom `komentar`
            $table->text('komentar');

            // Sesuai dengan kolom `tanggal`
            $table->timestamp('tanggal');
            
            // Membuat kolom `created_at` dan `updated_at`
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('akunreview');
    }
};