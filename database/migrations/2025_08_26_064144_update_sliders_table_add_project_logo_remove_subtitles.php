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
        Schema::table('sliders', function (Blueprint $table) {
            // Remove subtitle columns
            $table->dropColumn(['subtitle_en', 'subtitle_ar']);

            // Add project_logo column
            $table->string('project_logo')->nullable()->after('title_ar');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sliders', function (Blueprint $table) {
            // Add subtitle columns back
            $table->string('subtitle_en')->nullable()->after('title_ar');
            $table->string('subtitle_ar')->nullable()->after('subtitle_en');

            // Remove project_logo
            $table->dropColumn('project_logo');
        });
    }
};
