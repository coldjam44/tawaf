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
        Schema::table('contact_us', function (Blueprint $table) {
            // إضافة حقول الشركة
            $table->string('company_name_ar')->nullable()->after('id');
            $table->string('company_name_en')->nullable()->after('company_name_ar');
            $table->string('broker_registration_number')->nullable()->after('company_name_en');
            
            // إضافة حقول الاتصالات المتعددة (JSON)
            $table->json('phone_numbers')->nullable()->after('broker_registration_number');
            $table->json('email_addresses')->nullable()->after('phone_numbers');
            $table->json('locations')->nullable()->after('email_addresses');
            
            // إزالة الحقول القديمة
            $table->dropColumn(['phone_ar', 'phone_en', 'email', 'address_ar', 'address_en', 'working_hours_ar', 'working_hours_en']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contact_us', function (Blueprint $table) {
            // إعادة الحقول القديمة
            $table->string('phone_ar')->after('id');
            $table->string('phone_en')->after('phone_ar');
            $table->string('email')->after('phone_en');
            $table->text('address_ar')->after('email');
            $table->text('address_en')->after('address_ar');
            $table->string('working_hours_ar')->after('address_en');
            $table->string('working_hours_en')->after('working_hours_ar');
            
            // إزالة الحقول الجديدة
            $table->dropColumn([
                'company_name_ar',
                'company_name_en', 
                'broker_registration_number',
                'phone_numbers',
                'email_addresses',
                'locations'
            ]);
        });
    }
};
