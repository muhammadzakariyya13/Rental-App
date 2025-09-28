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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_akun')->constrained('akun', 'id')->onDelete('cascade');
            // Ubah referensi dari 'id' menjadi 'id_properti'
            $table->foreignId('id_properti')->constrained('properti', 'id_properti')->onDelete('cascade');
            $table->text('komentar');
            $table->integer('rating');
            $table->date('tanggal');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};