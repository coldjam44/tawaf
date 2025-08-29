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
        Schema::table('project_descriptions', function (Blueprint $table) {
            // Update the enum to include location_map
            $table->enum('section_type', ['about', 'architecture', 'why_choose', 'location', 'investment', 'location_map'])->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('project_descriptions', function (Blueprint $table) {
            // Revert back to original enum
            $table->enum('section_type', ['about', 'architecture', 'why_choose', 'location', 'investment'])->change();
        });
    }
};
