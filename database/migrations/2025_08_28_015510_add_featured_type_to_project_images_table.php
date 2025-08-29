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
            // First, we need to drop the enum constraint and recreate it
            $table->enum('type', ['interior', 'exterior', 'floorplan', 'featured'])->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('project_images', function (Blueprint $table) {
            $table->enum('type', ['interior', 'exterior', 'floorplan'])->change();
        });
    }
};
