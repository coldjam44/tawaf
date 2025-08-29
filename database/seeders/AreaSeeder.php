<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Area;

class AreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $areas = [
            [
                'name_ar' => 'وسط مدينة دبي',
                'name_en' => 'Downtown Dubai',
                'slug' => 'downtown-dubai',
                'about_community_overview_ar' => 'وسط مدينة دبي هو القلب النابض للمدينة، موطن برج خليفة الشهير ونافورة دبي. هذه المنطقة الفاخرة تجمع بين الأناقة والحداثة مع إطلالات خلابة على المدينة.',
                'about_community_overview_en' => 'Downtown Dubai is the beating heart of the city, home to the iconic Burj Khalifa and Dubai Fountain. This luxury district combines elegance and modernity with stunning city views.',
                'rental_sales_trends_ar' => 'شهدت المنطقة ارتفاعاً في أسعار الإيجار بنسبة 15% خلال العام الماضي. متوسط إيجار الشقة بغرفتين نوم يتراوح بين 120,000-180,000 درهم سنوياً.',
                'rental_sales_trends_en' => 'The area has seen a 15% increase in rental prices over the past year. Average rent for a 2-bedroom apartment ranges from AED 120,000-180,000 annually.',
                'roi_ar' => 'متوسط العائد على الاستثمار في وسط مدينة دبي يتراوح بين 6-8% سنوياً، مما يجعلها من أفضل المناطق للاستثمار العقاري.',
                'roi_en' => 'Average ROI in Downtown Dubai ranges from 6-8% annually, making it one of the best areas for real estate investment.',
                'things_to_do_perks_ar' => 'زيارة برج خليفة، مشاهدة نافورة دبي، التسوق في دبي مول، تناول الطعام في أفضل المطاعم العالمية، والاستمتاع بالحياة الليلية المميزة.',
                'things_to_do_perks_en' => 'Visit Burj Khalifa, watch Dubai Fountain, shop at Dubai Mall, dine at world-class restaurants, and enjoy the vibrant nightlife.'
            ],
            [
                'name_ar' => 'مرسى دبي',
                'name_en' => 'Dubai Marina',
                'slug' => 'dubai-marina',
                'about_community_overview_ar' => 'مرسى دبي هو أكبر مرسى من صنع الإنسان في العالم، يتميز بناطحات السحاب الفاخرة والمناظر البحرية الخلابة. المنطقة مثالية للعائلات والمهنيين.',
                'about_community_overview_en' => 'Dubai Marina is the world\'s largest man-made marina, featuring luxury skyscrapers and stunning waterfront views. The area is perfect for families and professionals.',
                'rental_sales_trends_ar' => 'أسعار الإيجار في مرسى دبي مستقرة مع زيادة طفيفة بنسبة 8%. متوسط إيجار الشقة بغرفتين نوم يتراوح بين 100,000-150,000 درهم سنوياً.',
                'rental_sales_trends_en' => 'Rental prices in Dubai Marina are stable with a slight 8% increase. Average rent for a 2-bedroom apartment ranges from AED 100,000-150,000 annually.',
                'roi_ar' => 'العائد على الاستثمار في مرسى دبي يتراوح بين 5-7% سنوياً، مع إمكانية تحقيق عوائد أعلى من خلال الإيجار قصير المدى.',
                'roi_en' => 'ROI in Dubai Marina ranges from 5-7% annually, with potential for higher returns through short-term rentals.',
                'things_to_do_perks_ar' => 'ركوب القوارب في المرسى، المشي على الكورنيش، التسوق في مارينا مول، تناول الطعام في المطاعم البحرية، وممارسة الرياضات المائية.',
                'things_to_do_perks_en' => 'Boat rides in the marina, walking along the promenade, shopping at Marina Mall, dining at waterfront restaurants, and water sports activities.'
            ],
            [
                'name_ar' => 'تلال الإمارات',
                'name_en' => 'Emirates Hills',
                'slug' => 'emirates-hills',
                'about_community_overview_ar' => 'تلال الإمارات هي منطقة سكنية فاخرة تضم فيلات مستقلة مع حدائق خاصة. المنطقة هادئة ومثالية للعائلات التي تبحث عن الخصوصية والرفاهية.',
                'about_community_overview_en' => 'Emirates Hills is a luxury residential area featuring standalone villas with private gardens. The area is quiet and perfect for families seeking privacy and luxury.',
                'rental_sales_trends_ar' => 'أسعار الإيجار في تلال الإمارات مرتفعة ومستقرة. متوسط إيجار الفيلا بثلاث غرف نوم يتراوح بين 300,000-500,000 درهم سنوياً.',
                'rental_sales_trends_en' => 'Rental prices in Emirates Hills are high and stable. Average rent for a 3-bedroom villa ranges from AED 300,000-500,000 annually.',
                'roi_ar' => 'العائد على الاستثمار في تلال الإمارات يتراوح بين 4-6% سنوياً، مع التركيز على الاستثمار طويل المدى.',
                'roi_en' => 'ROI in Emirates Hills ranges from 4-6% annually, focusing on long-term investment.',
                'things_to_do_perks_ar' => 'اللعب في نادي الإمارات للجولف، المشي في الحدائق الخاصة، الاستمتاع بالهدوء والخصوصية، والوصول السريع لمراكز التسوق.',
                'things_to_do_perks_en' => 'Play at Emirates Golf Club, walk in private gardens, enjoy tranquility and privacy, and quick access to shopping centers.'
            ],
            [
                'name_ar' => 'مزارع العرب',
                'name_en' => 'Arabian Ranches',
                'slug' => 'arabian-ranches',
                'about_community_overview_ar' => 'مزارع العرب هي مجتمع سكني عائلي يتميز بالمساحات الخضراء الواسعة والطبيعة الهادئة. المنطقة مثالية للعائلات التي تبحث عن الحياة الريفية مع وسائل الراحة الحديثة.',
                'about_community_overview_en' => 'Arabian Ranches is a family-oriented community featuring vast green spaces and peaceful nature. The area is perfect for families seeking rural living with modern amenities.',
                'rental_sales_trends_ar' => 'أسعار الإيجار في مزارع العرب مستقرة ومناسبة للعائلات. متوسط إيجار الفيلا بثلاث غرف نوم يتراوح بين 200,000-350,000 درهم سنوياً.',
                'rental_sales_trends_en' => 'Rental prices in Arabian Ranches are stable and family-friendly. Average rent for a 3-bedroom villa ranges from AED 200,000-350,000 annually.',
                'roi_ar' => 'العائد على الاستثمار في مزارع العرب يتراوح بين 5-7% سنوياً، مع استقرار في القيم العقارية.',
                'roi_en' => 'ROI in Arabian Ranches ranges from 5-7% annually, with stable property values.',
                'things_to_do_perks_ar' => 'ركوب الخيل في نادي مزارع العرب، المشي في الحدائق العامة، السباحة في المسابح المجتمعية، والاستمتاع بالطبيعة الخلابة.',
                'things_to_do_perks_en' => 'Horse riding at Arabian Ranches Golf Club, walking in public parks, swimming in community pools, and enjoying beautiful nature.'
            ],
            [
                'name_ar' => 'عقارات تلال دبي',
                'name_en' => 'Dubai Hills Estate',
                'slug' => 'dubai-hills-estate',
                'about_community_overview_ar' => 'عقارات تلال دبي هو مجتمع سكني حديث يتميز بالتصميم العصري والمساحات الخضراء الواسعة. المنطقة تجمع بين الرفاهية والطبيعة.',
                'about_community_overview_en' => 'Dubai Hills Estate is a modern residential community featuring contemporary design and vast green spaces. The area combines luxury with nature.',
                'rental_sales_trends_ar' => 'أسعار الإيجار في عقارات تلال دبي في ارتفاع مستمر. متوسط إيجار الشقة بغرفتين نوم يتراوح بين 80,000-120,000 درهم سنوياً.',
                'rental_sales_trends_en' => 'Rental prices in Dubai Hills Estate are continuously rising. Average rent for a 2-bedroom apartment ranges from AED 80,000-120,000 annually.',
                'roi_ar' => 'العائد على الاستثمار في عقارات تلال دبي يتراوح بين 6-8% سنوياً، مع إمكانية نمو أكبر في المستقبل.',
                'roi_en' => 'ROI in Dubai Hills Estate ranges from 6-8% annually, with potential for greater growth in the future.',
                'things_to_do_perks_ar' => 'اللعب في نادي تلال دبي للجولف، المشي في المنتزهات، التسوق في دبي هيلز مول، والاستمتاع بالمناظر الطبيعية الخلابة.',
                'things_to_do_perks_en' => 'Play at Dubai Hills Golf Club, walk in parks, shop at Dubai Hills Mall, and enjoy stunning natural views.'
            ]
        ];

        foreach ($areas as $area) {
            Area::updateOrCreate(
                ['slug' => $area['slug']],
                $area
            );
        }
    }
}
