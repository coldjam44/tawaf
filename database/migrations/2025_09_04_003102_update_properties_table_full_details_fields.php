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
        Schema::table('properties', function (Blueprint $table) {
            // Add new full details fields
            $table->text('propertyfulldetils_ar')->nullable()->after('propertyfulldetils');
            $table->text('propertyfulldetils_en')->nullable()->after('propertyfulldetils_ar');
            
            // Drop old fields
            $table->dropColumn(['propertyfulldetils', 'propertyinformation']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            // Recreate old fields
            $table->text('propertyfulldetils')->nullable()->after('propertyfeatures');
            $table->text('propertyinformation')->nullable()->after('propertyfulldetils');
            
            // Drop new fields
            $table->dropColumn(['propertyfulldetils_ar', 'propertyfulldetils_en']);
        });
    }
};
