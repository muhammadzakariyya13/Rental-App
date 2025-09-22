<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('akunreview', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_akun');
            $table->unsignedBigInteger('id_properti');
            $table->text('review_text');
            $table->integer('rating');
            $table->timestamps();

            // Change to reference 'id' in the users table
            $table->foreign('id_akun')->references('id')->on('users');
            $table->foreign('id_properti')->references('id_properti')->on('properti');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('akunreview');
    }
};