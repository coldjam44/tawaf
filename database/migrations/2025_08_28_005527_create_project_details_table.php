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
        Schema::create('project_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_id');
            $table->string('detail_ar'); // مثل: "Starting Price", "Offering type"
            $table->string('detail_en');
            $table->text('detail_value_ar'); // مثل: "Coming Soon", "2 to 5 Bedroom"
            $table->text('detail_value_en');
            $table->integer('order')->default(0); // للترتيب
            $table->timestamps();

            // Foreign key relationship
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_details');
    }
};
