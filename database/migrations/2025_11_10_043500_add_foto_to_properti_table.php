<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('properti_images', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_properti');
            $table->string('image_path');
            $table->string('image_name');
            $table->boolean('is_primary')->default(false);
            $table->integer('order_index')->default(0);
            $table->timestamps();

            $table->foreign('id_properti')->references('id_properti')->on('properti')->onDelete('cascade');
            $table->index(['id_properti', 'order_index']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('properti_images');
    }
};