<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use App\Models\Service;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        // Ensure table exists
        if (!Schema::hasTable('services')) {
            Schema::create('services', function (Blueprint $table) {
                $table->id();
                $table->string('title_ar');
                $table->string('title_en');
                $table->text('description_ar')->nullable();
                $table->text('description_en')->nullable();
                $table->string('image')->nullable();
                $table->string('contact_phone')->nullable();
                $table->boolean('request_service')->default(false);
                $table->timestamps();
            });
        }

        $defaults = [
            [
                'title_ar' => 'الاستشارات العقارية',
                'title_en' => 'Real Estate Consulting',
                'description_ar' => 'نساعدك في اتخاذ قرارات استثمارية صحيحة.',
                'description_en' => 'We help you make the right investment decisions.',
                'image' => null,
                'contact_phone' => '+971500000001',
                'request_service' => true,
            ],
            [
                'title_ar' => 'إدارة الممتلكات',
                'title_en' => 'Property Management',
                'description_ar' => 'خدمات إدارة شاملة لعقارك.',
                'description_en' => 'Comprehensive management services for your property.',
                'image' => null,
                'contact_phone' => '+971500000002',
                'request_service' => true,
            ],
            [
                'title_ar' => 'الوساطة العقارية',
                'title_en' => 'Real Estate Brokerage',
                'description_ar' => 'بيع وشراء وتأجير بكل سهولة.',
                'description_en' => 'Buy, sell, and rent with ease.',
                'image' => null,
                'contact_phone' => '+971500000003',
                'request_service' => false,
            ],
        ];

        foreach ($defaults as $row) {
            Service::firstOrCreate(
                [
                    'title_ar' => $row['title_ar'],
                    'title_en' => $row['title_en'],
                ],
                $row
            );
        }
    }
}


