<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Newsletter;

class NewsletterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // حذف البيانات الموجودة
        Newsletter::truncate();

        // إنشاء بيانات تجريبية
        Newsletter::create([
            'name' => 'أحمد محمد',
            'email' => 'ahmed@example.com',
            'phone' => '+971501234567',
            'message' => 'أريد معلومات عن المشاريع الجديدة في دبي'
        ]);

        Newsletter::create([
            'name' => 'سارة أحمد',
            'email' => 'sara@example.com',
            'phone' => '+971507654321',
            'message' => 'هل لديكم مشاريع في أبوظبي؟'
        ]);

        Newsletter::create([
            'name' => 'محمد علي',
            'email' => 'mohammed@example.com',
            'phone' => null,
            'message' => 'أريد الاشتراك في النشرة الإخبارية'
        ]);

        Newsletter::create([
            'name' => null,
            'email' => 'info@company.com',
            'phone' => '+97141234567',
            'message' => 'طلب استفسار عن الخدمات'
        ]);

        Newsletter::create([
            'name' => 'فاطمة حسن',
            'email' => null,
            'phone' => '+971509876543',
            'message' => 'أريد معرفة أسعار الشقق في المشاريع الجديدة'
        ]);

        $this->command->info('تم إنشاء بيانات تجريبية للنشرة الإخبارية بنجاح!');
    }
}
