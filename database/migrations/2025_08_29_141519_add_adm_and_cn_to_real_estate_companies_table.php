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
        Schema::table('real_estate_companies', function (Blueprint $table) {
            $table->string('adm_number')->nullable()->after('properties_communities_en');
            $table->string('cn_number')->nullable()->after('adm_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('real_estate_companies', function (Blueprint $table) {
            $table->dropColumn(['adm_number', 'cn_number']);
        });
    }
};
