<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Blog;
use App\Models\BlogImage;

class BlogSeeder extends Seeder
{
    public function run()
    {
        // 1. مقال عن العقارات في دبي
        $blog1 = Blog::create([
            'title_ar' => 'دليل شامل للاستثمار العقاري في دبي 2024',
            'title_en' => 'Complete Guide to Real Estate Investment in Dubai 2024',
            'content_ar' => '<p>دبي هي واحدة من أكثر المدن جاذبية للاستثمار العقاري في العالم. مع اقتصادها المتنامي والبنية التحتية المتطورة، توفر دبي فرصاً استثمارية فريدة للمستثمرين المحليين والدوليين.</p>

<h3>لماذا الاستثمار في دبي؟</h3>
<ul>
<li>اقتصاد مستقر ومتنامي</li>
<li>بنية تحتية متطورة</li>
<li>قوانين استثمارية مرنة</li>
<li>عائد استثماري مرتفع</li>
<li>موقع استراتيجي عالمي</li>
</ul>

<p>في هذا المقال، سنستكشف أهم الفرص الاستثمارية في السوق العقاري الدبي وأفضل المناطق للاستثمار.</p>',
            'content_en' => '<p>Dubai is one of the most attractive cities for real estate investment in the world. With its growing economy and advanced infrastructure, Dubai offers unique investment opportunities for local and international investors.</p>

<h3>Why Invest in Dubai?</h3>
<ul>
<li>Stable and growing economy</li>
<li>Advanced infrastructure</li>
<li>Flexible investment laws</li>
<li>High return on investment</li>
<li>Strategic global location</li>
</ul>

<p>In this article, we will explore the most important investment opportunities in the Dubai real estate market and the best areas for investment.</p>',
            'main_image' => null,
            'status' => 'published',
            'order_index' => 1,
            'is_active' => true,
            'published_at' => now()
        ]);

        // 2. مقال عن التصميم الداخلي
        $blog2 = Blog::create([
            'title_ar' => 'أحدث اتجاهات التصميم الداخلي للمنازل الفاخرة',
            'title_en' => 'Latest Interior Design Trends for Luxury Homes',
            'content_ar' => '<p>التصميم الداخلي للمنازل الفاخرة يتطور باستمرار مع ظهور اتجاهات جديدة ومبتكرة. في هذا المقال، نستعرض أحدث الاتجاهات في عالم التصميم الداخلي.</p>

<h3>الاتجاهات السائدة لعام 2024</h3>
<ul>
<li>التصميم المستدام والصديق للبيئة</li>
<li>استخدام التكنولوجيا الذكية</li>
<li>الألوان الطبيعية والمواد العضوية</li>
<li>التصميم المفتوح والمرن</li>
<li>اللمسات الشخصية والعائلية</li>
</ul>

<p>اكتشف كيف يمكنك تطبيق هذه الاتجاهات في منزلك لإنشاء مساحة فاخرة وعصرية.</p>',
            'content_en' => '<p>Interior design for luxury homes is constantly evolving with the emergence of new and innovative trends. In this article, we review the latest trends in the world of interior design.</p>

<h3>Prevailing Trends for 2024</h3>
<ul>
<li>Sustainable and environmentally friendly design</li>
<li>Use of smart technology</li>
<li>Natural colors and organic materials</li>
<li>Open and flexible design</li>
<li>Personal and family touches</li>
</ul>

<p>Discover how you can apply these trends in your home to create a luxurious and modern space.</p>',
            'main_image' => null,
            'status' => 'published',
            'order_index' => 2,
            'is_active' => true,
            'published_at' => now()
        ]);

        // 3. مقال عن التمويل العقاري
        $blog3 = Blog::create([
            'title_ar' => 'دليل التمويل العقاري: كل ما تحتاج معرفته',
            'title_en' => 'Real Estate Finance Guide: Everything You Need to Know',
            'content_ar' => '<p>التمويل العقاري هو أحد أهم الجوانب في عملية شراء العقار. فهم خيارات التمويل المتاحة يمكن أن يساعدك في اتخاذ قرارات استثمارية ذكية.</p>

<h3>أنواع التمويل العقاري</h3>
<ul>
<li>الرهن العقاري التقليدي</li>
<li>التمويل الإسلامي</li>
<li>التمويل الحكومي</li>
<li>التمويل من المطورين</li>
<li>التمويل الشخصي</li>
</ul>

<h3>نصائح للحصول على أفضل تمويل</h3>
<p>تعرف على كيفية تحسين ملفك الائتماني واختيار أفضل عروض التمويل المتاحة في السوق.</p>',
            'content_en' => '<p>Real estate financing is one of the most important aspects of the property purchase process. Understanding available financing options can help you make smart investment decisions.</p>

<h3>Types of Real Estate Financing</h3>
<ul>
<li>Traditional mortgage</li>
<li>Islamic financing</li>
<li>Government financing</li>
<li>Developer financing</li>
<li>Personal financing</li>
</ul>

<h3>Tips for Getting the Best Financing</h3>
<p>Learn how to improve your credit profile and choose the best financing offers available in the market.</p>',
            'main_image' => null,
            'status' => 'published',
            'order_index' => 3,
            'is_active' => true,
            'published_at' => now()
        ]);

        // 4. مقال عن المناطق السكنية
        $blog4 = Blog::create([
            'title_ar' => 'أفضل المناطق السكنية في الإمارات للعائلات',
            'title_en' => 'Best Residential Areas in UAE for Families',
            'content_ar' => '<p>اختيار المنطقة السكنية المناسبة هو قرار مهم يؤثر على جودة حياة العائلة. في هذا المقال، نستعرض أفضل المناطق السكنية في الإمارات للعائلات.</p>

<h3>معايير اختيار المنطقة السكنية</h3>
<ul>
<li>قرب المدارس والجامعات</li>
<li>توفر المرافق الطبية</li>
<li>البنية التحتية والمواصلات</li>
<li>الأمان والهدوء</li>
<li>المرافق الترفيهية</li>
</ul>

<h3>أفضل المناطق الموصى بها</h3>
<p>اكتشف المناطق التي تناسب احتياجات عائلتك وميزانيتك.</p>',
            'content_en' => '<p>Choosing the right residential area is an important decision that affects family quality of life. In this article, we review the best residential areas in the UAE for families.</p>

<h3>Criteria for Choosing a Residential Area</h3>
<ul>
<li>Proximity to schools and universities</li>
<li>Availability of medical facilities</li>
<li>Infrastructure and transportation</li>
<li>Safety and tranquility</li>
<li>Recreational facilities</li>
</ul>

<h3>Best Recommended Areas</h3>
<p>Discover areas that suit your family\'s needs and budget.</p>',
            'main_image' => null,
            'status' => 'draft',
            'order_index' => 4,
            'is_active' => true,
            'published_at' => null
        ]);

        // 5. مقال عن الاستثمار في الشقق
        $blog5 = Blog::create([
            'title_ar' => 'الاستثمار في الشقق: دليل شامل للمبتدئين',
            'title_en' => 'Apartment Investment: Complete Guide for Beginners',
            'content_ar' => '<p>الاستثمار في الشقق هو خيار شائع للمستثمرين المبتدئين. في هذا الدليل الشامل، نتعرف على أساسيات الاستثمار في الشقق.</p>

<h3>مزايا الاستثمار في الشقق</h3>
<ul>
<li>تكلفة أقل مقارنة بالفلل</li>
<li>سهولة الإدارة والتأجير</li>
<li>طلب مرتفع في السوق</li>
<li>عائد استثماري سريع</li>
<li>مرونة في البيع</li>
</ul>

<h3>نصائح للمستثمرين المبتدئين</h3>
<p>تعرف على كيفية اختيار الشقة المناسبة للاستثمار وتجنب الأخطاء الشائعة.</p>',
            'content_en' => '<p>Apartment investment is a common choice for beginner investors. In this comprehensive guide, we learn the basics of apartment investment.</p>

<h3>Advantages of Apartment Investment</h3>
<ul>
<li>Lower cost compared to villas</li>
<li>Easy management and rental</li>
<li>High market demand</li>
<li>Quick return on investment</li>
<li>Flexibility in selling</li>
</ul>

<h3>Tips for Beginner Investors</h3>
<p>Learn how to choose the right apartment for investment and avoid common mistakes.</p>',
            'main_image' => null,
            'status' => 'published',
            'order_index' => 5,
            'is_active' => true,
            'published_at' => now()
        ]);

        $this->command->info('Blog posts seeded successfully!');
    }
}
