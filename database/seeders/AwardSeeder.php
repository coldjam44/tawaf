<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Award;

class AwardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $awards = [
            [
                'title_ar' => 'أفضل شركة عقارية 2024',
                'title_en' => 'Best Real Estate Company 2024',
                'description_ar' => 'حصلنا على جائزة أفضل شركة عقارية لعام 2024 من قبل الهيئة الدولية للعقارات، وذلك تقديراً لتميزنا في مجال التطوير العقاري وخدمة العملاء.',
                'description_en' => 'We received the Best Real Estate Company 2024 award from the International Real Estate Authority, in recognition of our excellence in real estate development and customer service.',
                'year' => '2024',
                'category' => 'أفضل شركة عقارية',
                'order_index' => 1,
                'is_active' => true
            ],
            [
                'title_ar' => 'جائزة التميز في الخدمة',
                'title_en' => 'Excellence in Service Award',
                'description_ar' => 'تم منحنا جائزة التميز في الخدمة لعام 2023 من قبل مجلس الخدمات العقارية، تقديراً لالتزامنا بتقديم أفضل الخدمات لعملائنا.',
                'description_en' => 'We were awarded the Excellence in Service Award 2023 by the Real Estate Services Council, in recognition of our commitment to providing the best services to our clients.',
                'year' => '2023',
                'category' => 'التميز في الخدمة',
                'order_index' => 2,
                'is_active' => true
            ],
            [
                'title_ar' => 'شهادة الجودة العالمية',
                'title_en' => 'Global Quality Certification',
                'description_ar' => 'حصلنا على شهادة الجودة العالمية ISO 9001:2015، مما يؤكد التزامنا بأعلى معايير الجودة في جميع مشاريعنا.',
                'description_en' => 'We obtained the ISO 9001:2015 Global Quality Certification, confirming our commitment to the highest quality standards in all our projects.',
                'year' => '2023',
                'category' => 'الجودة العالمية',
                'order_index' => 3,
                'is_active' => true
            ],
            [
                'title_ar' => 'جائزة أفضل مشروع سكني',
                'title_en' => 'Best Residential Project Award',
                'description_ar' => 'فزنا بجائزة أفضل مشروع سكني لعام 2022 عن مشروع "فيلات الإمارات الفاخرة"، والذي تميز بتصميمه المبتكر ومرافقه المتطورة.',
                'description_en' => 'We won the Best Residential Project Award 2022 for the "Luxury Emirates Villas" project, which was distinguished by its innovative design and advanced facilities.',
                'year' => '2022',
                'category' => 'أفضل مشروع سكني',
                'order_index' => 4,
                'is_active' => true
            ],
            [
                'title_ar' => 'جائزة الابتكار في التصميم',
                'title_en' => 'Innovation in Design Award',
                'description_ar' => 'حصلنا على جائزة الابتكار في التصميم لعام 2021، تقديراً لتصاميمنا المبتكرة والحديثة التي تجمع بين الجمال والوظيفة.',
                'description_en' => 'We received the Innovation in Design Award 2021, in recognition of our innovative and modern designs that combine beauty and functionality.',
                'year' => '2021',
                'category' => 'الابتكار في التصميم',
                'order_index' => 5,
                'is_active' => true
            ]
        ];

        foreach ($awards as $awardData) {
            Award::create($awardData);
        }
    }
}
