ؤ<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sliders', function (Blueprint $table) {
            $table->id();

            // الصورة الخلفية
            $table->string('background_image');

            // النصوص الرئيسية بالإنجليزية
            $table->string('title_en')->nullable();
            $table->string('subtitle_en')->nullable();

            // النصوص الرئيسية بالعربية
            $table->string('title_ar')->nullable();
            $table->string('subtitle_ar')->nullable();

            // الأزرار
            $table->string('button1_text_en')->nullable();
            $table->string('button1_text_ar')->nullable();
            $table->string('button1_link')->nullable();

            $table->string('button2_text_en')->nullable();
            $table->string('button2_text_ar')->nullable();
            $table->string('button2_link')->nullable();

            // قائمة الميزات (Features) نخزنها كـ JSON لتعدد العناصر واللغات
            $table->json('features_en')->nullable();
            $table->json('features_ar')->nullable();

            // رابط الكتيب (Brochure PDF)
            $table->string('brochure_link')->nullable();

            // حالة السلايدر: نشط / غير نشط
            $table->boolean('status')->default(1);

            // ترتيب العرض
            $table->integer('order')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sliders');
    }
};
