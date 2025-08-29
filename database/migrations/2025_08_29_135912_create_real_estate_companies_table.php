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
        Schema::create('real_estate_companies', function (Blueprint $table) {
            $table->id();
            $table->string('company_logo')->nullable();
            $table->string('company_name_ar');
            $table->string('company_name_en');
            $table->text('short_description_ar')->nullable();
            $table->text('short_description_en')->nullable();
            $table->text('about_company_ar')->nullable();
            $table->text('about_company_en')->nullable();
            $table->json('company_images')->nullable();
            $table->string('contact_number')->nullable();
            $table->text('features_ar')->nullable();
            $table->text('features_en')->nullable();
            $table->text('properties_communities_ar')->nullable();
            $table->text('properties_communities_en')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('real_estate_companies');
    }
};
