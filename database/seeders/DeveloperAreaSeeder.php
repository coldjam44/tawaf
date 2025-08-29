<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Developer;
use App\Models\Area;

class DeveloperAreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sample developers
        $developers = [
            [
                'name_en' => 'Emaar Properties',
                'name_ar' => 'إعمار العقارية',
                'email' => 'info@emaar.com',
                'phone' => '+971-4-366-8888'
            ],
            [
                'name_en' => 'Nakheel Properties',
                'name_ar' => 'نخيل العقارية',
                'email' => 'info@nakheel.com',
                'phone' => '+971-4-390-3333'
            ],
            [
                'name_en' => 'Dubai Properties',
                'name_ar' => 'دبي العقارية',
                'email' => 'info@dubaiproperties.com',
                'phone' => '+971-4-318-8888'
            ],
            [
                'name_en' => 'Meraas',
                'name_ar' => 'ميراس',
                'email' => 'info@meraas.com',
                'phone' => '+971-4-317-7777'
            ],
            [
                'name_en' => 'Sobha Realty',
                'name_ar' => 'سوبها العقارية',
                'email' => 'info@sobha.com',
                'phone' => '+971-4-378-9999'
            ]
        ];

        foreach ($developers as $developer) {
            Developer::create($developer);
        }

        // Create sample areas
        $areas = [
            [
                'name_en' => 'Downtown Dubai',
                'name_ar' => 'وسط مدينة دبي',
                'slug' => 'downtown-dubai'
            ],
            [
                'name_en' => 'Dubai Marina',
                'name_ar' => 'مرسى دبي',
                'slug' => 'dubai-marina'
            ],
            [
                'name_en' => 'Palm Jumeirah',
                'name_ar' => 'جزيرة نخلة الجميرة',
                'slug' => 'palm-jumeirah'
            ],
            [
                'name_en' => 'Business Bay',
                'name_ar' => 'خليج الأعمال',
                'slug' => 'business-bay'
            ],
            [
                'name_en' => 'Jumeirah Beach Residence',
                'name_ar' => 'إقامة شاطئ الجميرة',
                'slug' => 'jbr'
            ],
            [
                'name_en' => 'Dubai Hills Estate',
                'name_ar' => 'عقارات تلال دبي',
                'slug' => 'dubai-hills-estate'
            ],
            [
                'name_en' => 'Arabian Ranches',
                'name_ar' => 'مزارع العرب',
                'slug' => 'arabian-ranches'
            ],
            [
                'name_en' => 'Emirates Hills',
                'name_ar' => 'تلال الإمارات',
                'slug' => 'emirates-hills'
            ]
        ];

        foreach ($areas as $area) {
            Area::create($area);
        }
    }
}
