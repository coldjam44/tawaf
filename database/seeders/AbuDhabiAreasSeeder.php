<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Area;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AbuDhabiAreasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define areas with their real image URLs
        $areas = [
            [
                'name_ar' => 'جزيرة السعديات',
                'name_en' => 'Saadiyat Island',
                'slug' => 'saadiyat-island',
                'image_url' => 'https://www.visitabudhabi.ae/-/media/visitabudhabi/plan-your-trip/where-to-stay/saadiyat-island/saadiyat-island-hero.jpg',
                'about_community_overview_ar' => 'جزيرة السعديات هي وجهة ثقافية عالمية تقع في أبوظبي، تضم متاحف عالمية ومشاريع سكنية فاخرة.',
                'about_community_overview_en' => 'Saadiyat Island is a global cultural destination in Abu Dhabi, featuring world-class museums and luxury residential projects.',
                'rental_sales_trends_ar' => 'تتميز بأسعار عقارية مرتفعة مع نمو مستمر في الطلب على العقارات الفاخرة.',
                'rental_sales_trends_en' => 'Features high property prices with consistent growth in demand for luxury properties.',
                'roi_ar' => 'عائد استثماري ممتاز للمستثمرين في العقارات الفاخرة.',
                'roi_en' => 'Excellent return on investment for luxury property investors.',
                'things_to_do_perks_ar' => 'متاحف عالمية، شواطئ فاخرة، منتجعات، ملاعب جولف، مراكز تسوق.',
                'things_to_do_perks_en' => 'World-class museums, luxury beaches, resorts, golf courses, shopping centers.'
            ],
            [
                'name_ar' => 'جزيرة الريم',
                'name_en' => 'Al Reem Island',
                'slug' => 'al-reem-island',
                'image_url' => 'https://www.visitabudhabi.ae/-/media/visitabudhabi/plan-your-trip/where-to-stay/al-reem-island/al-reem-island-hero.jpg',
                'about_community_overview_ar' => 'جزيرة الريم هي مجتمع سكني حديث يقع في قلب أبوظبي، يتميز بأبراج سكنية عالية ومشاريع متطورة.',
                'about_community_overview_en' => 'Al Reem Island is a modern residential community in the heart of Abu Dhabi, featuring high-rise towers and advanced projects.',
                'rental_sales_trends_ar' => 'أسعار متوسطة إلى عالية مع نمو مستقر في سوق العقارات.',
                'rental_sales_trends_en' => 'Medium to high prices with stable growth in the real estate market.',
                'roi_ar' => 'عائد استثماري جيد للمستثمرين في العقارات السكنية.',
                'roi_en' => 'Good return on investment for residential property investors.',
                'things_to_do_perks_ar' => 'مراكز تسوق، مطاعم، شواطئ، مرافق رياضية، خدمات طبية.',
                'things_to_do_perks_en' => 'Shopping centers, restaurants, beaches, sports facilities, medical services.'
            ],
            [
                'name_ar' => 'جزيرة ياس',
                'name_en' => 'Yas Island',
                'slug' => 'yas-island',
                'image_url' => 'https://www.visitabudhabi.ae/-/media/visitabudhabi/plan-your-trip/where-to-stay/yas-island/yas-island-hero.jpg',
                'about_community_overview_ar' => 'جزيرة ياس هي وجهة ترفيهية عالمية تضم حلبة الفورمولا 1 ومدينة وارنر براذرز ومرافق ترفيهية متعددة.',
                'about_community_overview_en' => 'Yas Island is a global entertainment destination featuring Formula 1 circuit, Warner Bros World, and multiple entertainment facilities.',
                'rental_sales_trends_ar' => 'أسعار مرتفعة بسبب الطبيعة الترفيهية والاستثمارية للمنطقة.',
                'rental_sales_trends_en' => 'High prices due to the entertainment and investment nature of the area.',
                'roi_ar' => 'عائد استثماري ممتاز للمشاريع الترفيهية والسياحية.',
                'roi_en' => 'Excellent return on investment for entertainment and tourism projects.',
                'things_to_do_perks_ar' => 'حلبة الفورمولا 1، مدينة وارنر براذرز، شواطئ، فنادق فاخرة، مراكز تسوق.',
                'things_to_do_perks_en' => 'Formula 1 circuit, Warner Bros World, beaches, luxury hotels, shopping centers.'
            ],
            [
                'name_ar' => 'جزيرة فهد',
                'name_en' => 'Fahid Island',
                'slug' => 'fahid-island',
                'image_url' => 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?w=800&h=600&fit=crop',
                'about_community_overview_ar' => 'جزيرة فهد هي منطقة سكنية حديثة تتميز بمشاريع عقارية متطورة ومرافق حديثة.',
                'about_community_overview_en' => 'Fahid Island is a modern residential area featuring advanced real estate projects and modern facilities.',
                'rental_sales_trends_ar' => 'أسعار متوسطة مع إمكانيات نمو جيدة في المستقبل.',
                'rental_sales_trends_en' => 'Medium prices with good growth potential in the future.',
                'roi_ar' => 'عائد استثماري معقول للمستثمرين على المدى المتوسط.',
                'roi_en' => 'Reasonable return on investment for medium-term investors.',
                'things_to_do_perks_ar' => 'مرافق رياضية، حدائق، خدمات تعليمية، مراكز طبية.',
                'things_to_do_perks_en' => 'Sports facilities, parks, educational services, medical centers.'
            ],
            [
                'name_ar' => 'مدينة زايد',
                'name_en' => 'Zayed City',
                'slug' => 'zayed-city',
                'image_url' => 'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?w=800&h=600&fit=crop',
                'about_community_overview_ar' => 'مدينة زايد هي مدينة حديثة تتميز بتخطيط حضري متطور ومرافق متكاملة.',
                'about_community_overview_en' => 'Zayed City is a modern city featuring advanced urban planning and integrated facilities.',
                'rental_sales_trends_ar' => 'أسعار معقولة مع نمو مستقر في سوق العقارات.',
                'rental_sales_trends_en' => 'Reasonable prices with stable growth in the real estate market.',
                'roi_ar' => 'عائد استثماري جيد للمستثمرين في العقارات السكنية والتجارية.',
                'roi_en' => 'Good return on investment for residential and commercial property investors.',
                'things_to_do_perks_ar' => 'مراكز تسوق، مطاعم، حدائق، مرافق رياضية، خدمات حكومية.',
                'things_to_do_perks_en' => 'Shopping centers, restaurants, parks, sports facilities, government services.'
            ],
            [
                'name_ar' => 'مدينة مصدر',
                'name_en' => 'Masdar City',
                'slug' => 'masdar-city',
                'image_url' => 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?w=800&h=600&fit=crop',
                'about_community_overview_ar' => 'مدينة مصدر هي أول مدينة خالية من الكربون في العالم، تتميز بالتقنيات المستدامة والطاقة النظيفة.',
                'about_community_overview_en' => 'Masdar City is the world\'s first carbon-neutral city, featuring sustainable technologies and clean energy.',
                'rental_sales_trends_ar' => 'أسعار متوسطة إلى عالية بسبب الطبيعة المستدامة والتقنية للمدينة.',
                'rental_sales_trends_en' => 'Medium to high prices due to the sustainable and technological nature of the city.',
                'roi_ar' => 'عائد استثماري ممتاز للمشاريع المستدامة والتقنية.',
                'roi_en' => 'Excellent return on investment for sustainable and technological projects.',
                'things_to_do_perks_ar' => 'تقنيات مستدامة، مراكز بحثية، مرافق تعليمية، حدائق خضراء.',
                'things_to_do_perks_en' => 'Sustainable technologies, research centers, educational facilities, green parks.'
            ],
            [
                'name_ar' => 'الجرف',
                'name_en' => 'Al Jurf',
                'slug' => 'al-jurf',
                'image_url' => 'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?w=800&h=600&fit=crop',
                'about_community_overview_ar' => 'الجرف هي منطقة ساحلية تتميز بشواطئ جميلة ومشاريع سكنية فاخرة.',
                'about_community_overview_en' => 'Al Jurf is a coastal area featuring beautiful beaches and luxury residential projects.',
                'rental_sales_trends_ar' => 'أسعار مرتفعة بسبب الموقع الساحلي المميز.',
                'rental_sales_trends_en' => 'High prices due to the premium coastal location.',
                'roi_ar' => 'عائد استثماري ممتاز للمشاريع الساحلية الفاخرة.',
                'roi_en' => 'Excellent return on investment for luxury coastal projects.',
                'things_to_do_perks_ar' => 'شواطئ فاخرة، منتجعات، مرافق رياضية مائية، مطاعم بحرية.',
                'things_to_do_perks_en' => 'Luxury beaches, resorts, water sports facilities, seafood restaurants.'
            ],
            [
                'name_ar' => 'جزيرة الحديريات',
                'name_en' => 'Al Hudayriat Island',
                'slug' => 'al-hudayriat-island',
                'image_url' => 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?w=800&h=600&fit=crop',
                'about_community_overview_ar' => 'جزيرة الحديريات هي وجهة ترفيهية ورياضية حديثة تتميز بمرافق متطورة.',
                'about_community_overview_en' => 'Al Hudayriat Island is a modern entertainment and sports destination featuring advanced facilities.',
                'rental_sales_trends_ar' => 'أسعار متوسطة مع إمكانيات نمو كبيرة.',
                'rental_sales_trends_en' => 'Medium prices with great growth potential.',
                'roi_ar' => 'عائد استثماري جيد للمشاريع الترفيهية والرياضية.',
                'roi_en' => 'Good return on investment for entertainment and sports projects.',
                'things_to_do_perks_ar' => 'مرافق رياضية، شواطئ، حدائق، مطاعم، أنشطة ترفيهية.',
                'things_to_do_perks_en' => 'Sports facilities, beaches, parks, restaurants, entertainment activities.'
            ],
            [
                'name_ar' => 'جزيرة الجبيل',
                'name_en' => 'Al Jubail Island',
                'slug' => 'al-jubail-island',
                'image_url' => 'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?w=800&h=600&fit=crop',
                'about_community_overview_ar' => 'جزيرة الجبيل هي منطقة سكنية حديثة تتميز بمشاريع عقارية متطورة.',
                'about_community_overview_en' => 'Al Jubail Island is a modern residential area featuring advanced real estate projects.',
                'rental_sales_trends_ar' => 'أسعار متوسطة مع نمو مستقر.',
                'rental_sales_trends_en' => 'Medium prices with stable growth.',
                'roi_ar' => 'عائد استثماري معقول للمستثمرين.',
                'roi_en' => 'Reasonable return on investment for investors.',
                'things_to_do_perks_ar' => 'مرافق سكنية حديثة، حدائق، خدمات تعليمية، مراكز طبية.',
                'things_to_do_perks_en' => 'Modern residential facilities, parks, educational services, medical centers.'
            ],
            [
                'name_ar' => 'الشامخة',
                'name_en' => 'Al Shamkha',
                'slug' => 'al-shamkha',
                'image_url' => 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?w=800&h=600&fit=crop',
                'about_community_overview_ar' => 'الشامخة هي منطقة سكنية حديثة تتميز بمشاريع عقارية متطورة ومرافق حديثة.',
                'about_community_overview_en' => 'Al Shamkha is a modern residential area featuring advanced real estate projects and modern facilities.',
                'rental_sales_trends_ar' => 'أسعار معقولة مع إمكانيات نمو جيدة.',
                'rental_sales_trends_en' => 'Reasonable prices with good growth potential.',
                'roi_ar' => 'عائد استثماري جيد للمستثمرين على المدى الطويل.',
                'roi_en' => 'Good return on investment for long-term investors.',
                'things_to_do_perks_ar' => 'مرافق سكنية حديثة، حدائق، خدمات تعليمية، مراكز طبية.',
                'things_to_do_perks_en' => 'Modern residential facilities, parks, educational services, medical centers.'
            ],
            [
                'name_ar' => 'جزيرة المارية',
                'name_en' => 'Al Maryah Island',
                'slug' => 'al-maryah-island',
                'image_url' => 'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?w=800&h=600&fit=crop',
                'about_community_overview_ar' => 'جزيرة المارية هي مركز مالي وتجاري مهم في أبوظبي، تتميز بأبراج تجارية فاخرة.',
                'about_community_overview_en' => 'Al Maryah Island is an important financial and commercial center in Abu Dhabi, featuring luxury commercial towers.',
                'rental_sales_trends_ar' => 'أسعار مرتفعة بسبب الطبيعة التجارية والمالية للمنطقة.',
                'rental_sales_trends_en' => 'High prices due to the commercial and financial nature of the area.',
                'roi_ar' => 'عائد استثماري ممتاز للمشاريع التجارية والمالية.',
                'roi_en' => 'Excellent return on investment for commercial and financial projects.',
                'things_to_do_perks_ar' => 'مراكز مالية، مطاعم فاخرة، مراكز تسوق، فنادق فاخرة.',
                'things_to_do_perks_en' => 'Financial centers, luxury restaurants, shopping centers, luxury hotels.'
            ],
            [
                'name_ar' => 'جزيرة رمحان',
                'name_en' => 'Ramhan Island',
                'slug' => 'ramhan-island',
                'image_url' => 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?w=800&h=600&fit=crop',
                'about_community_overview_ar' => 'جزيرة رمحان هي منطقة سكنية حديثة تتميز بمشاريع عقارية متطورة.',
                'about_community_overview_en' => 'Ramhan Island is a modern residential area featuring advanced real estate projects.',
                'rental_sales_trends_ar' => 'أسعار معقولة مع إمكانيات نمو جيدة.',
                'rental_sales_trends_en' => 'Reasonable prices with good growth potential.',
                'roi_ar' => 'عائد استثماري معقول للمستثمرين.',
                'roi_en' => 'Reasonable return on investment for investors.',
                'things_to_do_perks_ar' => 'مرافق سكنية حديثة، حدائق، خدمات تعليمية، مراكز طبية.',
                'things_to_do_perks_en' => 'Modern residential facilities, parks, educational services, medical centers.'
            ]
        ];

        // Create areas directory if it doesn't exist
        $areasDir = public_path('areas/images');
        if (!file_exists($areasDir)) {
            mkdir($areasDir, 0755, true);
        }

        foreach ($areas as $areaData) {
            // Check if area already exists
            $existingArea = Area::where('slug', $areaData['slug'])->first();
            if ($existingArea) {
                $this->command->info("Area {$areaData['name_en']} already exists, skipping...");
                continue;
            }

            try {
                // Download and save image
                $imageUrl = $areaData['image_url'];
                $imageContent = Http::timeout(30)->get($imageUrl);
                
                if ($imageContent->successful()) {
                    $imageName = $areaData['slug'] . '_' . time() . '.jpg';
                    $imagePath = $areasDir . '/' . $imageName;
                    
                    file_put_contents($imagePath, $imageContent->body());
                    
                    // Create area record
                    $area = Area::create([
                        'name_ar' => $areaData['name_ar'],
                        'name_en' => $areaData['name_en'],
                        'slug' => $areaData['slug'],
                        'main_image' => $imageName,
                        'about_community_overview_ar' => $areaData['about_community_overview_ar'],
                        'about_community_overview_en' => $areaData['about_community_overview_en'],
                        'rental_sales_trends_ar' => $areaData['rental_sales_trends_ar'],
                        'rental_sales_trends_en' => $areaData['rental_sales_trends_en'],
                        'roi_ar' => $areaData['roi_ar'],
                        'roi_en' => $areaData['roi_en'],
                        'things_to_do_perks_ar' => $areaData['things_to_do_perks_ar'],
                        'things_to_do_perks_en' => $areaData['things_to_do_perks_en']
                    ]);
                    
                    $this->command->info("Created area: {$areaData['name_en']} with image: {$imageName}");
                } else {
                    $this->command->error("Failed to download image for {$areaData['name_en']}");
                    
                    // Create area without image
                    $area = Area::create([
                        'name_ar' => $areaData['name_ar'],
                        'name_en' => $areaData['name_en'],
                        'slug' => $areaData['slug'],
                        'about_community_overview_ar' => $areaData['about_community_overview_ar'],
                        'about_community_overview_en' => $areaData['about_community_overview_en'],
                        'rental_sales_trends_ar' => $areaData['rental_sales_trends_ar'],
                        'rental_sales_trends_en' => $areaData['rental_sales_trends_en'],
                        'roi_ar' => $areaData['roi_ar'],
                        'roi_en' => $areaData['roi_en'],
                        'things_to_do_perks_ar' => $areaData['things_to_do_perks_ar'],
                        'things_to_do_perks_en' => $areaData['things_to_do_perks_en']
                    ]);
                    
                    $this->command->info("Created area: {$areaData['name_en']} without image");
                }
                
            } catch (\Exception $e) {
                $this->command->error("Error creating area {$areaData['name_en']}: " . $e->getMessage());
                
                // Create area without image as fallback
                try {
                    $area = Area::create([
                        'name_ar' => $areaData['name_ar'],
                        'name_en' => $areaData['name_en'],
                        'slug' => $areaData['slug'],
                        'about_community_overview_ar' => $areaData['about_community_overview_ar'],
                        'about_community_overview_en' => $areaData['about_community_overview_en'],
                        'rental_sales_trends_ar' => $areaData['rental_sales_trends_ar'],
                        'rental_sales_trends_en' => $areaData['rental_sales_trends_en'],
                        'roi_ar' => $areaData['roi_ar'],
                        'roi_en' => $areaData['roi_en'],
                        'things_to_do_perks_ar' => $areaData['things_to_do_perks_ar'],
                        'things_to_do_perks_en' => $areaData['things_to_do_perks_en']
                    ]);
                    
                    $this->command->info("Created area: {$areaData['name_en']} without image (fallback)");
                } catch (\Exception $e2) {
                    $this->command->error("Failed to create area {$areaData['name_en']}: " . $e2->getMessage());
                }
            }
        }
        
        $this->command->info('Abu Dhabi Areas seeder completed successfully!');
    }
}
