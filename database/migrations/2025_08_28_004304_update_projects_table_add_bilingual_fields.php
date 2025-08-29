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
        Schema::table('projects', function (Blueprint $table) {
            // Add new bilingual fields
            $table->string('prj_title_en')->nullable()->after('prj_title');
            $table->text('prj_description_en')->nullable()->after('prj_description');
            
            // Rename existing fields to Arabic versions
            $table->renameColumn('prj_title', 'prj_title_ar');
            $table->renameColumn('prj_description', 'prj_description_ar');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            // Revert the changes
            $table->renameColumn('prj_title_ar', 'prj_title');
            $table->renameColumn('prj_description_ar', 'prj_description');
            
            $table->dropColumn(['prj_title_en', 'prj_description_en']);
        });
    }
};
