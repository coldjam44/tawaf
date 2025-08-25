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
        Schema::create('ramadanoffers', function (Blueprint $table) {
            $table->id();
            $table->integer('price');
            $table->integer('suhoor_price');
            $table->integer('breakfast_price');

            $table->integer('number_of_night');
            $table->text('image');
            $table->string('hotelname_en');
            $table->string('hotelname_ar');
            $table->string('roomtype_en');
            $table->string('roomtype_ar');
            $table->text('title_ar');
            $table->text('title_en');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ramadanoffers');
    }
};
