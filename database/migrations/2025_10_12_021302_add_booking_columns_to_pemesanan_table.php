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
        Schema::table('pemesanan', function (Blueprint $table) {
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_selesai')->nullable();
            $table->decimal('total_harga', 15, 2)->nullable();
            $table->string('status', 50)->nullable();
            $table->text('catatan')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pemesanan', function (Blueprint $table) {
            $table->dropColumn(['tanggal_mulai', 'tanggal_selesai', 'total_harga', 'status', 'catatan']);
        });
    }
};
