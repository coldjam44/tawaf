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
        Schema::create('about_us_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('about_us_section_id')->constrained('about_us_sections')->onDelete('cascade');
            $table->string('image_path');
            $table->string('alt_text_ar')->nullable();
            $table->string('alt_text_en')->nullable();
            $table->string('caption_ar')->nullable();
            $table->string('caption_en')->nullable();
            $table->integer('order_index')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('about_us_images');
    }
};
