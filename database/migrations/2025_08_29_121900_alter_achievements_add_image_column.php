<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('achievements') && !Schema::hasColumn('achievements', 'image')) {
            Schema::table('achievements', function (Blueprint $table) {
                $table->string('image')->nullable()->after('name_en');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('achievements') && Schema::hasColumn('achievements', 'image')) {
            Schema::table('achievements', function (Blueprint $table) {
                $table->dropColumn('image');
            });
        }
    }
};


