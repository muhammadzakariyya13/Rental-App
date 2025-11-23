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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('order_id')->index();
            $table->string('payment_id')->unique()->nullable();
            $table->decimal('amount', 15, 2);
            $table->enum('status', ['pending', 'paid', 'failed', 'expired', 'cancelled'])->default('pending');
            $table->string('method')->nullable(); // bank_transfer, credit_card, e_wallet, etc
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone');
            $table->timestamp('expired_at');
            $table->timestamp('paid_at')->nullable();
            $table->json('gateway_response')->nullable(); // Store API response
            $table->string('payment_url')->nullable(); // Payment redirect URL
            $table->text('notes')->nullable();
            $table->timestamps();
            
            // Indexes for performance
            $table->index(['status', 'created_at']);
            $table->index(['expired_at']);
            $table->index(['customer_email']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
