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
        Schema::create('pemesanan', function (Blueprint $table) {
            $table->id('id_pemesanan');
            $table->unsignedBigInteger('id_properti');
            $table->integer('lama_sewa');
            $table->enum('status_pemesanan', ['pending', 'confirmed', 'cancelled', 'completed']);
            $table->string('metode_pembayaran');
            $table->enum('status_pembayaran', ['unpaid', 'paid', 'refunded']);
            $table->date('tanggal_pemesanan');
            $table->timestamps();
            
            // Foreign key ke tabel properti (asumsi tabel properti ada/akan dibuat)
            // $table->foreign('id_properti')->references('id')->on('propertis');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemesanan');
    }
};