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
        Schema::create('hotelpricings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hotel_id')->constrained('hotels')->onDelete('cascade');
            $table->date('start_date'); // تاريخ بداية الفترة
            $table->date('end_date'); // تاريخ نهاية الفترة
            $table->decimal('price', 10, 2); // السعر لهذه الفترة
               $table->decimal('discount_price', 10, 2)->nullable();

          $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hotelpricings');
    }
};
