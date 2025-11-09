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
            $table->string('payment_transaction_id')->nullable()->after('metode_pembayaran');
            $table->string('payment_url')->nullable()->after('payment_transaction_id');
            $table->string('payment_status')->nullable()->after('payment_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pemesanan', function (Blueprint $table) {
            $table->dropColumn(['payment_transaction_id', 'payment_url', 'payment_status']);
        });
    }
};
