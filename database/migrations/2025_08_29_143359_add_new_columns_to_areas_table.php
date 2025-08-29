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
        Schema::table('areas', function (Blueprint $table) {
            $table->text('about_community_overview_ar')->nullable()->after('slug');
            $table->text('about_community_overview_en')->nullable()->after('about_community_overview_ar');
            $table->text('rental_sales_trends_ar')->nullable()->after('about_community_overview_en');
            $table->text('rental_sales_trends_en')->nullable()->after('rental_sales_trends_ar');
            $table->text('roi_ar')->nullable()->after('rental_sales_trends_en');
            $table->text('roi_en')->nullable()->after('roi_ar');
            $table->text('things_to_do_perks_ar')->nullable()->after('roi_en');
            $table->text('things_to_do_perks_en')->nullable()->after('things_to_do_perks_ar');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('areas', function (Blueprint $table) {
            $table->dropColumn([
                'about_community_overview_ar',
                'about_community_overview_en',
                'rental_sales_trends_ar',
                'rental_sales_trends_en',
                'roi_ar',
                'roi_en',
                'things_to_do_perks_ar',
                'things_to_do_perks_en'
            ]);
        });
    }
};
