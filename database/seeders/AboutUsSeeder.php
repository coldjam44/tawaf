<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AboutUsSection;
use App\Models\AboutUsImage;

class AboutUsSeeder extends Seeder
{
    public function run()
    {
        // 1. مقدمة الشركة
        $companyIntro = AboutUsSection::create([
            'section_name' => 'company_intro',
            'title_ar' => 'مرحباً بكم في شركة العقارات المتميزة',
            'title_en' => 'Welcome to Our Distinguished Real Estate Company',
            'content_ar' => 'نحن شركة رائدة في مجال العقارات، متخصصون في تقديم أفضل الخدمات العقارية لعملائنا الكرام. نتميز بخبرة طويلة في السوق العقاري الإماراتي ونقدم حلولاً شاملة لجميع احتياجاتكم العقارية.',
            'content_en' => 'We are a leading real estate company, specializing in providing the best real estate services to our valued clients. We are distinguished by our long experience in the UAE real estate market and provide comprehensive solutions for all your real estate needs.',
            'main_image' => null,
            'order_index' => 1,
            'is_active' => true
        ]);

        // 2. رؤيتنا
        $vision = AboutUsSection::create([
            'section_name' => 'our_vision',
            'title_ar' => 'رؤيتنا',
            'title_en' => 'Our Vision',
            'content_ar' => 'نسعى لأن نكون الشركة العقارية الأولى في المنطقة، من خلال تقديم خدمات عالية الجودة وتطوير مشاريع مبتكرة تلبي تطلعات عملائنا وتساهم في تطوير المجتمع.',
            'content_en' => 'We strive to be the leading real estate company in the region, by providing high-quality services and developing innovative projects that meet our clients\' aspirations and contribute to community development.',
            'main_image' => null,
            'order_index' => 2,
            'is_active' => true
        ]);

        // 3. مهمتنا
        $mission = AboutUsSection::create([
            'section_name' => 'our_mission',
            'title_ar' => 'مهمتنا',
            'title_en' => 'Our Mission',
            'content_ar' => 'نلتزم بتقديم خدمات عقارية متميزة ومبتكرة، مع التركيز على جودة البناء والتصميم المعماري الفريد، وضمان رضا العملاء من خلال الشفافية والمصداقية في جميع معاملاتنا.',
            'content_en' => 'We are committed to providing distinguished and innovative real estate services, focusing on construction quality and unique architectural design, while ensuring customer satisfaction through transparency and credibility in all our transactions.',
            'main_image' => null,
            'order_index' => 3,
            'is_active' => true
        ]);

        // 4. قيمنا
        $values = AboutUsSection::create([
            'section_name' => 'our_values',
            'title_ar' => 'قيمنا الأساسية',
            'title_en' => 'Our Core Values',
            'content_ar' => 'نؤمن بقيم أساسية تشكل أساس عملنا: الجودة العالية، الشفافية، المصداقية، الابتكار، والالتزام تجاه عملائنا ومجتمعنا. هذه القيم توجهنا في كل خطوة نخطوها نحو التميز.',
            'content_en' => 'We believe in core values that form the foundation of our work: high quality, transparency, credibility, innovation, and commitment to our clients and community. These values guide us in every step we take towards excellence.',
            'main_image' => null,
            'order_index' => 4,
            'is_active' => true
        ]);

        // 5. شركاؤنا
        $partners = AboutUsSection::create([
            'section_name' => 'our_partners',
            'title_ar' => 'شركاؤنا الاستراتيجيون',
            'title_en' => 'Our Strategic Partners',
            'content_ar' => 'نعمل مع نخبة من الشركات والمؤسسات الرائدة في مجال العقارات والبناء، مما يمكننا من تقديم أفضل الخدمات والحلول لعملائنا. شراكاتنا الاستراتيجية تضمن لنا الوصول لأحدث التقنيات وأفضل المواد.',
            'content_en' => 'We work with elite companies and leading institutions in real estate and construction, enabling us to provide the best services and solutions to our clients. Our strategic partnerships ensure access to the latest technologies and best materials.',
            'main_image' => null,
            'order_index' => 5,
            'is_active' => true
        ]);

        // إضافة صور للشركاء
        $partnerImages = [
            'partner1.jpg',
            'partner2.jpg',
            'partner3.jpg',
            'partner4.jpg',
            'partner5.jpg',
            'partner6.jpg'
        ];

        foreach ($partnerImages as $index => $imageName) {
            AboutUsImage::create([
                'about_us_section_id' => $partners->id,
                'image_path' => $imageName,
                'alt_text_ar' => 'شريك استراتيجي ' . ($index + 1),
                'alt_text_en' => 'Strategic Partner ' . ($index + 1),
                'caption_ar' => 'شريك استراتيجي متميز',
                'caption_en' => 'Distinguished Strategic Partner',
                'order_index' => $index,
                'is_active' => true
            ]);
        }

        // 6. إنجازاتنا
        $achievements = AboutUsSection::create([
            'section_name' => 'our_achievements',
            'title_ar' => 'إنجازاتنا',
            'title_en' => 'Our Achievements',
            'content_ar' => 'على مدار سنوات من العمل الجاد والالتزام، حققنا إنجازات عديدة تشمل تطوير أكثر من 50 مشروع عقاري ناجح، وخدمة آلاف العملاء الراضين، وحصولنا على العديد من الجوائز والتقديرات في مجال العقارات.',
            'content_en' => 'Over years of hard work and commitment, we have achieved many accomplishments including developing more than 50 successful real estate projects, serving thousands of satisfied clients, and receiving numerous awards and recognitions in the real estate field.',
            'main_image' => null,
            'order_index' => 6,
            'is_active' => true
        ]);

        // 7. فريق العمل
        $team = AboutUsSection::create([
            'section_name' => 'our_team',
            'title_ar' => 'فريق العمل المتميز',
            'title_en' => 'Our Distinguished Team',
            'content_ar' => 'نفخر بفريق عملنا المتميز من الخبراء والمتخصصين في مجال العقارات. فريقنا يضم نخبة من المهندسين والمصممين والمستشارين العقاريين ذوي الخبرة الطويلة، والذين يعملون بجد لضمان تقديم أفضل الخدمات لعملائنا.',
            'content_en' => 'We are proud of our distinguished team of experts and specialists in the real estate field. Our team includes elite engineers, designers, and experienced real estate consultants who work hard to ensure providing the best services to our clients.',
            'main_image' => null,
            'order_index' => 7,
            'is_active' => true
        ]);

        $this->command->info('About Us sections seeded successfully!');
    }
}
