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
        Schema::create('projects', function (Blueprint $table) {
            $table->id(); // id تلقائي
            $table->unsignedBigInteger('prj_developerId');
            $table->unsignedBigInteger('prj_areaId');
            $table->string('prj_title');
            $table->text('prj_description')->nullable();
            $table->string('prj_brochurefile')->nullable();
            $table->string('prj_adm')->nullable();
            $table->string('prj_cn')->nullable();
            $table->string('prj_projectNumber')->nullable();
            $table->string('prj_MadhmounPermitNumber')->nullable();
            $table->string('prj_floorplan')->nullable();
            $table->timestamps();

            // لو عايز تعمل foreign key:
            // $table->foreign('prj_developerId')->references('id')->on('developers')->onDelete('cascade');
            // $table->foreign('prj_areaId')->references('id')->on('areas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
