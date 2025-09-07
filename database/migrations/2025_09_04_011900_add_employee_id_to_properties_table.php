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
            // Add employee_id field after propertypaymentplan
            $table->unsignedBigInteger('employee_id')->nullable()->after('propertypaymentplan');
            
            // Add foreign key constraint
            $table->foreign('employee_id')->references('id')->on('developers')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            // Drop foreign key constraint first
            $table->dropForeign(['employee_id']);
            
            // Drop the column
            $table->dropColumn('employee_id');
        });
    }
};
