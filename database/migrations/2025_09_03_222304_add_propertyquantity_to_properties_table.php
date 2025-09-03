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
            // Add quantity column
            $table->integer('propertyquantity')->default(1)->after('propertyarea');
            
            // Remove email and phone columns as they will come from employee
            $table->dropColumn(['propertymail', 'propertyphonenumber']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            // Remove quantity column
            $table->dropColumn('propertyquantity');
            
            // Add back email and phone columns
            $table->string('propertymail')->nullable();
            $table->string('propertyphonenumber')->nullable();
        });
    }
};
