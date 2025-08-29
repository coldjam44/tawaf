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
        Schema::create('project_descriptions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_id');
            $table->enum('section_type', ['about', 'architecture', 'why_choose', 'location', 'investment']);
            $table->string('title_ar')->nullable();
            $table->string('title_en')->nullable();
            $table->longText('content_ar')->nullable();
            $table->longText('content_en')->nullable();
            $table->integer('order_index')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Foreign key relationship
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
            
            // Indexes for better performance
            $table->index(['project_id', 'section_type']);
            $table->index('order_index');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_descriptions');
    }
};
