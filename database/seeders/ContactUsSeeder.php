<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ContactUs;

class ContactUsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // حذف البيانات الموجودة
        ContactUs::truncate();

        // إنشاء بيانات تجريبية
        ContactUs::create([
            'company_name_ar' => 'شركة العقارات المتقدمة',
            'company_name_en' => 'Advanced Real Estate Company',
            'broker_registration_number' => 'RERA-123456',
            'phone_numbers' => [
                [
                    'number' => '+971501234567',
                    'type' => 'mobile'
                ],
                [
                    'number' => '+97141234567',
                    'type' => 'landline'
                ],
                [
                    'number' => '+97141234568',
                    'type' => 'fax'
                ]
            ],
            'email_addresses' => [
                [
                    'email' => 'info@advancedrealestate.ae',
                    'type' => 'general'
                ],
                [
                    'email' => 'support@advancedrealestate.ae',
                    'type' => 'support'
                ],
                [
                    'email' => 'sales@advancedrealestate.ae',
                    'type' => 'sales'
                ]
            ],
            'locations' => [
                [
                    'address_ar' => 'برج الإمارات، الطابق 15، شارع الشيخ زايد، دبي، الإمارات العربية المتحدة',
                    'address_en' => 'Emirates Tower, Floor 15, Sheikh Zayed Road, Dubai, UAE',
                    'map_embed' => '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3609.1234567890123!2d55.2744!3d25.2048!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMjXCsDEyJzE3LjMiTiA1NcKwMTYnMjcuOSJF!5e0!3m2!1sen!2sae!4v1234567890123" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>'
                ],
                [
                    'address_ar' => 'مركز أبوظبي التجاري، الطابق 8، شارع الكورنيش، أبوظبي، الإمارات العربية المتحدة',
                    'address_en' => 'Abu Dhabi Commercial Center, Floor 8, Corniche Street, Abu Dhabi, UAE',
                    'map_embed' => '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3609.9876543210987!2d54.3773!3d24.4539!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMjTCsDI3JzE0LjAiTiA1NMKwMjInNDAuMyJF!5e0!3m2!1sen!2sae!4v1234567890124" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>'
                ]
            ],
            'map_embed' => '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3609.1234567890123!2d55.2744!3d25.2048!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMjXCsDEyJzE3LjMiTiA1NcKwMTYnMjcuOSJF!5e0!3m2!1sen!2sae!4v1234567890123" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>'
        ]);

        $this->command->info('تم إنشاء بيانات تجريبية لصفحة اتصل بنا بنجاح!');
    }
}
