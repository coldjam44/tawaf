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
        Schema::table('project_images', function (Blueprint $table) {
            // Add 'main' to the existing enum type
            $table->enum('type', ['interior', 'exterior', 'floorplan', 'featured', 'main'])->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('project_images', function (Blueprint $table) {
            // Remove 'main' from the enum type (revert to original)
            $table->enum('type', ['interior', 'exterior', 'floorplan', 'featured'])->change();
        });
    }
};
