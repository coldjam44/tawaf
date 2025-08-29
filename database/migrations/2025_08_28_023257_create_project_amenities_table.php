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
        Schema::create('project_amenities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_id');
            $table->enum('amenity_type', [
                'infinity_pool',
                'concierge_services',
                'retail_fnb',
                'bbq_area',
                'cinema_game_room',
                'gym',
                'wellness_facilities',
                'splash_pad',
                'sauna_wellness',
                'multipurpose_court'
            ]);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Foreign key relationship
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
            
            // Indexes for better performance
            $table->index(['project_id', 'amenity_type']);
            $table->index('is_active');
            
            // Unique constraint to prevent duplicate amenities for same project
            $table->unique(['project_id', 'amenity_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_amenities');
    }
};
