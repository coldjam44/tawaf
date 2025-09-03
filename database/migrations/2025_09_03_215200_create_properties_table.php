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
        Schema::create('properties', function (Blueprint $table) {
            $table->id('propertyid'); // Primary key
            
            // ========================================
            // BASIC PROPERTY INFORMATION
            // ========================================
            $table->foreignId('propertyproject')->constrained('projects')->onDelete('cascade');
            $table->json('propertyimages')->nullable()->comment('Array of image paths');
            $table->decimal('propertyprice', 15, 2)->comment('Property price in AED');
            $table->tinyInteger('propertyrooms')->unsigned()->comment('Number of bedrooms');
            $table->tinyInteger('propertybathrooms')->unsigned()->comment('Number of bathrooms');
            $table->decimal('propertyarea', 8, 2)->comment('Total area in square meters');
            $table->string('propertyloaction')->comment('Property location/area');
            
            // ========================================
            // SALES & FINANCIAL INFORMATION
            // ========================================
            $table->text('propertypaymentplan')->nullable()->comment('Payment plan details');
            $table->date('propertyhandover')->nullable()->comment('Handover date');
            
            // ========================================
            // CONTACT INFORMATION
            // ========================================
            $table->string('propertymail')->nullable()->comment('Contact email');
            $table->string('propertyphonenumber')->nullable()->comment('Contact phone number');
            
            // ========================================
            // PROPERTY DETAILS
            // ========================================
            $table->text('propertyfeatures')->nullable()->comment('Property features and amenities');
            $table->longText('propertyfulldetils')->nullable()->comment('Full property details');
            $table->text('propertyinformation')->nullable()->comment('Additional property information');
            
            // ========================================
            // TIMESTAMPS
            // ========================================
            $table->timestamps();
            
            // ========================================
            // INDEXES FOR PERFORMANCE
            // ========================================
            $table->index('propertyproject');
            $table->index('propertyprice');
            $table->index('propertyloaction');
            $table->index('propertyhandover');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
