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
        Schema::create('hotelinhomes', function (Blueprint $table) {
            $table->id();
            $table->text('title_ar');
            $table->text('title_en');
            $table->text('description_ar');
            $table->text('description_en');
            //$table->foreignId('hotel_id')->constrained('hotels')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hotelinhomes');
    }
};
