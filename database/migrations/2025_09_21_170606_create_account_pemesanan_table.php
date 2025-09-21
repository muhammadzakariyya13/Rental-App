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
        Schema::create('account_pemesanan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_akun');
            $table->unsignedBigInteger('id_pemesanan');
            $table->timestamps();
            
            // Make sure the table name matches exactly what's in your database
            $table->foreign('id_akun')->references('id_akun')->on('account')->onDelete('cascade');
            $table->foreign('id_pemesanan')->references('id_pemesanan')->on('pemesanan')->onDelete('cascade');
            
            $table->unique(['id_akun', 'id_pemesanan']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('account_pemesanan');
    }
};