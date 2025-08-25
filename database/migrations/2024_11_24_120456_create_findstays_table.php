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
        Schema::create('findstays', function (Blueprint $table) {
            $table->id();
            $table->string('check_in_ar');
            $table->string('check_in_en');
            $table->string('check_out_ar');
            $table->string('check_out_en');
            //$table->text('place_en');
            //$table->text('place_ar');
            $table->integer('numberofroom');
            $table->integer('numberofadult')->nullable();
            $table->integer('numberofchild')->nullable();
                      $table->json('age_child')->nullable();

            $table->foreignId('place_id')->constrained('places')->onDelete('cascade');




            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('findstays');
    }
};
