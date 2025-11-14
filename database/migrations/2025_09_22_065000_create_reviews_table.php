<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id('id_review');
            $table->unsignedBigInteger('id_properti');
            $table->unsignedBigInteger('id_penyewa'); 
            $table->integer('rating');
            $table->text('review')->nullable();
            $table->boolean('is_approved')->default(true);
            $table->timestamp('tanggal_review')->useCurrent();
            $table->timestamps();
            $table->text('pemilik_reply')->nullable()->after('review');
            $table->timestamp('reply_date')->nullable()->after('pemilik_reply');

            $table->foreign('id_properti')->references('id_properti')->on('properti')->onDelete('cascade');
            // GANTI dari 'users' menjadi 'akun'
            $table->foreign('id_penyewa')->references('id')->on('akun')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropColumn(['pemilik_reply', 'reply_date']);
        });
    }
};