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
        Schema::create('kontraks', function (Blueprint $table) {
            $table->id('id_kontrak');
            $table->unsignedBigInteger('id_properti');
            $table->unsignedBigInteger('id_pemesanan');
            $table->date('tgl_mulai_sewa');
            $table->date('tgl_akhir_sewa');
            $table->decimal('harga_sewa', 10, 2);
            $table->timestamps();

            $table->foreign('id_properti')->references('id_properti')->on('properti')->onDelete('cascade');
            $table->foreign('id_pemesanan')->references('id_pemesanan')->on('pemesanan')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kontraks');
    }
};