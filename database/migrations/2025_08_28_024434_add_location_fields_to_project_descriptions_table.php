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
            // Add location-specific fields
            $table->string('location_image')->nullable()->after('content_en');
            $table->string('google_location')->nullable()->after('location_image');
            $table->string('location_address_ar')->nullable()->after('google_location');
            $table->string('location_address_en')->nullable()->after('location_address_ar');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('project_descriptions', function (Blueprint $table) {
            $table->dropColumn([
                'location_image',
                'google_location',
                'location_address_ar',
                'location_address_en'
            ]);
        });
    }
};
