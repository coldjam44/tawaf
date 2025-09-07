<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Blog;
use App\Models\BlogImage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class BlogWithImagesSeeder extends Seeder
{
    public function run()
    {
        // Array of real estate related images from Unsplash
        $mainImages = [
            'https://images.unsplash.com/photo-1560518883-ce09059eeffa?w=800&h=600&fit=crop',
            'https://images.unsplash.com/photo-1564013799919-ab600027ffc6?w=800&h=600&fit=crop',
            'https://images.unsplash.com/photo-1570129477492-45c003edd2be?w=800&h=600&fit=crop',
            'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?w=800&h=600&fit=crop',
            'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?w=800&h=600&fit=crop',
        ];

        $additionalImages = [
            'https://images.unsplash.com/photo-1560518883-ce09059eeffa?w=400&h=300&fit=crop',
            'https://images.unsplash.com/photo-1564013799919-ab600027ffc6?w=400&h=300&fit=crop',
            'https://images.unsplash.com/photo-1570129477492-45c003edd2be?w=400&h=300&fit=crop',
            'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?w=400&h=300&fit=crop',
            'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?w=400&h=300&fit=crop',
            'https://images.unsplash.com/photo-1582407947304-fd86f028f716?w=400&h=300&fit=crop',
            'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?w=400&h=300&fit=crop',
            'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?w=400&h=300&fit=crop',
        ];

        $blogs = [
            [
                'title_ar' => 'دليل الاستثمار العقاري للمبتدئين',
                'title_en' => 'Real Estate Investment Guide for Beginners',
                'content_ar' => '<p>الاستثمار العقاري من أفضل أنواع الاستثمارات طويلة المدى. في هذا الدليل، سنتعرف على أساسيات الاستثمار العقاري وكيفية البدء في هذا المجال المربح.</p>

<h3>فوائد الاستثمار العقاري:</h3>
<ul>
<li>عائد استثماري مستقر</li>
<li>حماية من التضخم</li>
<li>إمكانية الاستفادة من الرافعة المالية</li>
<li>تنويع المحفظة الاستثمارية</li>
</ul>

<h3>نصائح للمبتدئين:</h3>
<ol>
<li>ابدأ بدراسة السوق المحلي</li>
<li>حدد ميزانيتك بوضوح</li>
<li>اختر الموقع المناسب</li>
<li>استشر الخبراء</li>
<li>لا تستثمر أكثر من قدرتك المالية</li>
</ol>',
                'content_en' => '<p>Real estate investment is one of the best long-term investment types. In this guide, we will learn about the basics of real estate investment and how to start in this profitable field.</p>

<h3>Benefits of Real Estate Investment:</h3>
<ul>
<li>Stable investment returns</li>
<li>Protection against inflation</li>
<li>Ability to leverage financing</li>
<li>Portfolio diversification</li>
</ul>

<h3>Tips for Beginners:</h3>
<ol>
<li>Start by studying the local market</li>
<li>Clearly define your budget</li>
<li>Choose the right location</li>
<li>Consult with experts</li>
<li>Don\'t invest more than your financial capacity</li>
</ol>',
                'main_image_index' => 0,
                'additional_images_count' => 3
            ],
            [
                'title_ar' => 'نصائح ذهبية لشراء منزل أحلامك',
                'title_en' => 'Golden Tips for Buying Your Dream Home',
                'content_ar' => '<p>شراء منزل هو أحد أهم القرارات المالية في حياتك. في هذا المقال، نقدم لك 10 نصائح ذهبية لمساعدتك في اتخاذ القرار الصحيح.</p>

<h3>النصائح الأساسية:</h3>
<ul>
<li>حدد ميزانيتك بوضوح</li>
<li>اختر الموقع المناسب</li>
<li>فحص العقار بدقة</li>
<li>تأكد من الأوراق القانونية</li>
<li>استشر الخبراء</li>
<li>فكر في المستقبل</li>
<li>لا تتسرع في القرار</li>
<li>قارن العروض المتاحة</li>
<li>تأكد من المرافق</li>
<li>خطط للصيانة</li>
</ul>',
                'content_en' => '<p>Buying a home is one of the most important financial decisions in your life. In this article, we provide you with 10 golden tips to help you make the right decision.</p>

<h3>Essential Tips:</h3>
<ul>
<li>Clearly define your budget</li>
<li>Choose the right location</li>
<li>Inspect the property carefully</li>
<li>Verify legal documents</li>
<li>Consult with experts</li>
<li>Think about the future</li>
<li>Don\'t rush the decision</li>
<li>Compare available offers</li>
<li>Ensure facilities are available</li>
<li>Plan for maintenance</li>
</ul>',
                'main_image_index' => 1,
                'additional_images_count' => 4
            ],
            [
                'title_ar' => 'اتجاهات السوق العقاري 2024',
                'title_en' => 'Real Estate Market Trends 2024',
                'content_ar' => '<p>السوق العقاري في تطور مستمر. في هذا المقال، نستعرض أهم الاتجاهات المتوقعة في السوق العقاري لعام 2024.</p>

<h3>الاتجاهات الرئيسية:</h3>
<ul>
<li>زيادة الطلب على العقارات الذكية</li>
<li>التركيز على الاستدامة والبيئة</li>
<li>نمو الاستثمار في الضواحي</li>
<li>تطور التكنولوجيا العقارية</li>
<li>تغيير أنماط العمل والسكن</li>
</ul>

<h3>توقعات السوق:</h3>
<p>تعرف على التوقعات المستقبلية للسوق العقاري وكيفية الاستفادة من هذه الاتجاهات.</p>',
                'content_en' => '<p>The real estate market is constantly evolving. In this article, we review the most important expected trends in the real estate market for 2024.</p>

<h3>Main Trends:</h3>
<ul>
<li>Increased demand for smart properties</li>
<li>Focus on sustainability and environment</li>
<li>Growth in suburban investment</li>
<li>Development of real estate technology</li>
<li>Changing work and living patterns</li>
</ul>

<h3>Market Predictions:</h3>
<p>Learn about future real estate market predictions and how to benefit from these trends.</p>',
                'main_image_index' => 2,
                'additional_images_count' => 2
            ],
            [
                'title_ar' => 'دليل الصيانة المنزلية',
                'title_en' => 'Home Maintenance Guide',
                'content_ar' => '<p>الصيانة المنزلية المنتظمة هي مفتاح الحفاظ على قيمة منزلك وضمان راحتك. في هذا الدليل، نقدم لك نصائح شاملة للصيانة المنزلية.</p>

<h3>أعمال الصيانة الدورية:</h3>
<ul>
<li>فحص أنظمة التكييف والتدفئة</li>
<li>تنظيف وصيانة السباكة</li>
<li>فحص الأسلاك الكهربائية</li>
<li>صيانة الأبواب والنوافذ</li>
<li>تنظيف السقف والجدران</li>
</ul>

<h3>نصائح للصيانة الوقائية:</h3>
<p>تعلم كيفية منع المشاكل قبل حدوثها وتوفير المال على المدى الطويل.</p>',
                'content_en' => '<p>Regular home maintenance is the key to maintaining your home\'s value and ensuring your comfort. In this guide, we provide you with comprehensive home maintenance tips.</p>

<h3>Periodic Maintenance Tasks:</h3>
<ul>
<li>Inspect air conditioning and heating systems</li>
<li>Clean and maintain plumbing</li>
<li>Check electrical wiring</li>
<li>Maintain doors and windows</li>
<li>Clean roof and walls</li>
</ul>

<h3>Preventive Maintenance Tips:</h3>
<p>Learn how to prevent problems before they occur and save money in the long run.</p>',
                'main_image_index' => 3,
                'additional_images_count' => 3
            ],
            [
                'title_ar' => 'التأمين العقاري: حماية استثمارك',
                'title_en' => 'Real Estate Insurance: Protect Your Investment',
                'content_ar' => '<p>التأمين العقاري هو وسيلة مهمة لحماية استثمارك العقاري من المخاطر المختلفة. في هذا المقال، نتعرف على أنواع التأمين العقاري وكيفية اختيار الأنسب.</p>

<h3>أنواع التأمين العقاري:</h3>
<ul>
<li>تأمين الممتلكات</li>
<li>تأمين المسؤولية المدنية</li>
<li>تأمين الكوارث الطبيعية</li>
<li>تأمين المحتويات</li>
<li>تأمين الإيجار</li>
</ul>

<h3>كيفية اختيار التأمين المناسب:</h3>
<p>تعرف على العوامل التي يجب مراعاتها عند اختيار بوليصة التأمين المناسبة لعقارك.</p>',
                'content_en' => '<p>Real estate insurance is an important way to protect your real estate investment from various risks. In this article, we learn about types of real estate insurance and how to choose the most suitable one.</p>

<h3>Types of Real Estate Insurance:</h3>
<ul>
<li>Property insurance</li>
<li>Civil liability insurance</li>
<li>Natural disaster insurance</li>
<li>Contents insurance</li>
<li>Rental insurance</li>
</ul>

<h3>How to Choose the Right Insurance:</h3>
<p>Learn about the factors to consider when choosing the right insurance policy for your property.</p>',
                'main_image_index' => 4,
                'additional_images_count' => 2
            ]
        ];

        foreach ($blogs as $index => $blogData) {
            // Download and save main image
            $mainImageName = $this->downloadImage($mainImages[$blogData['main_image_index']], 'main', $index + 1);
            
            // Create blog
            $blog = Blog::create([
                'title_ar' => $blogData['title_ar'],
                'title_en' => $blogData['title_en'],
                'content_ar' => $blogData['content_ar'],
                'content_en' => $blogData['content_en'],
                'main_image' => $mainImageName,
                'status' => 'published',
                'order_index' => $index + 1,
                'is_active' => true,
                'published_at' => now()
            ]);

            // Download and save additional images
            for ($i = 0; $i < $blogData['additional_images_count']; $i++) {
                $imageIndex = ($index * 3 + $i) % count($additionalImages);
                $imageName = $this->downloadImage($additionalImages[$imageIndex], 'additional', $blog->id, $i);
                
                BlogImage::create([
                    'blog_id' => $blog->id,
                    'image_path' => $imageName,
                    'alt_text_ar' => 'صورة إضافية للتدوينة',
                    'alt_text_en' => 'Additional blog image',
                    'caption_ar' => 'صورة توضيحية',
                    'caption_en' => 'Illustrative image',
                    'order_index' => $i,
                    'is_active' => true
                ]);
            }
        }

        $this->command->info('Blogs with real images seeded successfully!');
    }

    private function downloadImage($url, $type, $id, $index = 0)
    {
        try {
            $response = Http::timeout(30)->get($url);
            
            if ($response->successful()) {
                $extension = 'jpg'; // Default extension
                
                // Try to get extension from URL
                $pathInfo = pathinfo(parse_url($url, PHP_URL_PATH));
                if (isset($pathInfo['extension'])) {
                    $extension = $pathInfo['extension'];
                }
                
                $fileName = time() . '_' . $type . '_' . $id . '_' . $index . '.' . $extension;
                
                if ($type === 'main') {
                    $uploadPath = public_path('blogsfiles/main-images');
                } else {
                    $uploadPath = public_path('blogsfiles/images');
                }
                
                // Create directory if it doesn't exist
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }
                
                $filePath = $uploadPath . '/' . $fileName;
                file_put_contents($filePath, $response->body());
                
                return $fileName;
            }
        } catch (\Exception $e) {
            $this->command->error("Failed to download image: " . $e->getMessage());
        }
        
        return null;
    }
}
