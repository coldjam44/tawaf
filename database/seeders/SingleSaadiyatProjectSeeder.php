<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Project;
use App\Models\ProjectDetail;
use App\Models\ProjectDescription;
use App\Models\ProjectAmenity;
use App\Models\ProjectContentBlock;
use App\Models\ProjectImage;
use App\Models\Area;
use App\Models\Developer;
use App\Models\RealEstateCompany;
use Illuminate\Support\Facades\Http;

class SingleSaadiyatProjectSeeder extends Seeder
{
    public function run(): void
    {
        // Get Saadiyat Island area
        $saadiyatArea = Area::where('slug', 'saadiyat-island')->first();
        if (!$saadiyatArea) {
            $this->command->error('Saadiyat Island area not found!');
            return;
        }

        // Get or create developer
        $developer = Developer::firstOrCreate(
            ['name_en' => 'Aldar Properties'],
            [
                'name_ar' => 'ألدار العقارية',
                'email' => 'info@aldar.com',
                'phone' => '+971 2 810 5555'
            ]
        );

        // Get or create company
        $company = RealEstateCompany::firstOrCreate(
            ['company_name_en' => 'Aldar Real Estate'],
            [
                'company_name_ar' => 'ألدار العقارية',
                'contact_number' => '+971 2 810 5555',
                'short_description_en' => 'Leading real estate development company in Abu Dhabi',
                'short_description_ar' => 'شركة رائدة في التطوير العقاري في أبوظبي'
            ]
        );

        // Create single comprehensive project
        $project = Project::create([
            'prj_areaId' => $saadiyatArea->id,
            'company_id' => $company->id,
            'prj_title_ar' => 'سعديات ريزورت',
            'prj_title_en' => 'Saadiyat Resort',
            'prj_description_ar' => 'مشروع سكني فاخر في جزيرة السعديات يتميز بإطلالات بحرية خلابة ومرافق عالمية المستوى. يوفر المشروع أسلوب حياة راقي مع تصاميم معمارية مبتكرة ومرافق متطورة تلائم أنماط الحياة الحديثة.',
            'prj_description_en' => 'A luxury residential project on Saadiyat Island featuring stunning sea views and world-class amenities. The project offers an elegant lifestyle with innovative architectural designs and modern facilities that suit contemporary living patterns.',
            'prj_projectNumber' => 'SAAD-2025-001',
            'prj_MadhmounPermitNumber' => 'PERMIT-SAAD-2025-001',
            'is_sent_to_bot' => false
        ]);

        $this->command->info("✅ Created project: {$project->prj_title_en}");

        // Add comprehensive project details
        $this->addProjectDetails($project);
        
        // Add project descriptions
        $this->addProjectDescriptions($project);
        
        // Add project amenities
        $this->addProjectAmenities($project);
        
        // Add project content blocks
        $this->addProjectContentBlocks($project);
        
        // Add project images with actual downloads
        $this->addProjectImages($project);
        
        $this->command->info('🎉 Single Saadiyat Project seeder completed successfully!');
    }

    private function addProjectDetails($project)
    {
        $details = [
            [
                'detail_ar' => 'السعر ابتداءً من',
                'detail_en' => 'Starting Price',
                'detail_value_ar' => '1,500,000 درهم',
                'detail_value_en' => 'AED 1,500,000',
                'order' => 1
            ],
            [
                'detail_ar' => 'نوع العرض',
                'detail_en' => 'Offering Type',
                'detail_value_ar' => 'للبيع',
                'detail_value_en' => 'For Sale',
                'order' => 2
            ],
            [
                'detail_ar' => 'نوع العقار',
                'detail_en' => 'Property Type',
                'detail_value_ar' => 'شقق وفلل وتاون هاوس',
                'detail_value_en' => 'Apartments, Villas & Townhouses',
                'order' => 3
            ],
            [
                'detail_ar' => 'عدد الغرف',
                'detail_en' => 'Bedrooms',
                'detail_value_ar' => '1 إلى 5 غرف نوم',
                'detail_value_en' => '1 to 5 Bedrooms',
                'order' => 4
            ],
            [
                'detail_ar' => 'خطة السداد',
                'detail_en' => 'Payment Plan',
                'detail_value_ar' => '40/60',
                'detail_value_en' => '40/60',
                'order' => 5
            ],
            [
                'detail_ar' => 'التسليم',
                'detail_en' => 'Handover',
                'detail_value_ar' => 'الربع 3 من عام 2025',
                'detail_value_en' => 'Q3 2025',
                'order' => 6
            ],
            [
                'detail_ar' => 'نسبة الإنجاز',
                'detail_en' => 'Progress',
                'detail_value_ar' => 'قيد الإنشاء',
                'detail_value_en' => 'Under Construction',
                'order' => 7
            ],
            [
                'detail_ar' => 'المساحة',
                'detail_en' => 'Area',
                'detail_value_ar' => '800 - 3,500 قدم مربع',
                'detail_value_en' => '800 - 3,500 sq ft',
                'order' => 8
            ],
            [
                'detail_ar' => 'المطور',
                'detail_en' => 'Developer',
                'detail_value_ar' => 'ألدار العقارية',
                'detail_value_en' => 'Aldar Properties',
                'order' => 9
            ],
            [
                'detail_ar' => 'الموقع',
                'detail_en' => 'Location',
                'detail_value_ar' => 'جزيرة السعديات، أبوظبي',
                'detail_value_en' => 'Saadiyat Island, Abu Dhabi',
                'order' => 10
            ]
        ];

        foreach ($details as $detail) {
            ProjectDetail::create([
                'project_id' => $project->id,
                'detail_ar' => $detail['detail_ar'],
                'detail_en' => $detail['detail_en'],
                'detail_value_ar' => $detail['detail_value_ar'],
                'detail_value_en' => $detail['detail_value_en'],
                'order' => $detail['order']
            ]);
        }

        $this->command->info("✅ Added " . count($details) . " project details");
    }

    private function addProjectDescriptions($project)
    {
        $descriptions = [
            [
                'section_type' => 'about',
                'title_ar' => 'حول المشروع',
                'title_en' => 'About the Project',
                'content_ar' => 'سعديات ريزورت هو مشروع سكني فاخر يقع في قلب جزيرة السعديات، أحد أكثر المناطق حيوية في أبوظبي. يتميز المشروع بإطلالات بحرية خلابة ومرافق عالمية المستوى، مما يوفر أسلوب حياة راقي ومتميز للسكان.',
                'content_en' => 'Saadiyat Resort is a luxury residential project located in the heart of Saadiyat Island, one of the most vibrant areas in Abu Dhabi. The project features stunning sea views and world-class amenities, providing an elegant and distinguished lifestyle for residents.',
                'order_index' => 1
            ],
            [
                'section_type' => 'architecture',
                'title_ar' => 'التصميم المعماري',
                'title_en' => 'Architecture',
                'content_ar' => 'يتميز المشروع بتصاميم معمارية عصرية تجمع بين الأناقة والوظائفية. تم تصميم الوحدات السكنية بعناية فائقة لتوفير أقصى درجات الراحة والخصوصية مع الاستفادة من الإطلالات البحرية الخلابة والطبيعة الخضراء المحيطة.',
                'content_en' => 'The project features contemporary architectural designs that combine elegance and functionality. Residential units are carefully designed to provide maximum comfort and privacy while taking advantage of stunning sea views and surrounding greenery.',
                'order_index' => 2
            ],
            [
                'section_type' => 'why_choose',
                'title_ar' => 'لماذا تختار سعديات ريزورت؟',
                'title_en' => 'Why Choose Saadiyat Resort?',
                'content_ar' => 'موقع استراتيجي في جزيرة السعديات، مرافق ترفيهية متكاملة، تصاميم عصرية، خطط دفع مرنة، ومطور موثوق مع سجل حافل في التطوير العقاري الفاخر. بالإضافة إلى قرب المشروع من الشواطئ والمرافق السياحية.',
                'content_en' => 'Strategic location on Saadiyat Island, integrated recreational facilities, contemporary designs, flexible payment plans, and a trusted developer with a proven track record in luxury real estate development. Plus proximity to beaches and tourist facilities.',
                'order_index' => 3
            ],
            [
                'section_type' => 'location',
                'title_ar' => 'الموقع والوصولية',
                'title_en' => 'Location & Accessibility',
                'content_ar' => 'يقع المشروع في جزيرة السعديات، أحد أكثر المناطق حيوية في أبوظبي. يتميز الموقع بقربه من الشواطئ والمرافق الترفيهية والمؤسسات التعليمية والطبية. كما يسهل الوصول إليه عبر شبكة مواصلات متطورة.',
                'content_en' => 'The project is located on Saadiyat Island, one of the most vibrant areas in Abu Dhabi. The location is distinguished by its proximity to beaches, recreational facilities, educational and medical institutions. It is also easily accessible through an advanced transportation network.',
                'order_index' => 4
            ],
            [
                'section_type' => 'investment',
                'title_ar' => 'فرص الاستثمار',
                'title_en' => 'Investment Opportunities',
                'content_ar' => 'جزيرة السعديات تعد من أفضل المناطق للاستثمار العقاري في أبوظبي مع إمكانية تحقيق عوائد استثمارية ممتازة. المشروع يوفر فرصة استثمارية فريدة في موقع متميز مع إمكانية نمو قوية في القيمة العقارية.',
                'content_en' => 'Saadiyat Island is one of the best areas for real estate investment in Abu Dhabi with the potential for excellent investment returns. The project provides a unique investment opportunity in a prime location with strong potential for real estate value growth.',
                'order_index' => 5
            ]
        ];

        foreach ($descriptions as $description) {
            ProjectDescription::create([
                'project_id' => $project->id,
                'section_type' => $description['section_type'],
                'title_ar' => $description['title_ar'],
                'title_en' => $description['title_en'],
                'content_ar' => $description['content_ar'],
                'content_en' => $description['content_en'],
                'order_index' => $description['order_index']
            ]);
        }

        $this->command->info("✅ Added project descriptions");
    }

    private function addProjectAmenities($project)
    {
        $amenities = [
            'infinity_pool',
            'concierge_services',
            'retail_fnb',
            'bbq_area',
            'cinema_game_room',
            'gym',
            'wellness_facilities',
            'splash_pad',
            'sauna_wellness',
            'multipurpose_court'
        ];

        foreach ($amenities as $amenity) {
            ProjectAmenity::create([
                'project_id' => $project->id,
                'amenity_type' => $amenity,
                'is_active' => true
            ]);
        }

        $this->command->info("✅ Added project amenities");
    }

    private function addProjectContentBlocks($project)
    {
        $contentBlocks = [
            [
                'title_ar' => 'المرافق الترفيهية',
                'title_en' => 'Recreational Facilities',
                'content_ar' => 'يضم المشروع مجموعة متنوعة من المرافق الترفيهية التي تلبي جميع احتياجات السكان، بما في ذلك حمامات السباحة اللانهائية، صالة الألعاب الرياضية المتطورة، مناطق الشواء المخصصة، وصالة السينما والألعاب.',
                'content_en' => 'The project includes a variety of recreational facilities that meet all residents\' needs, including infinity swimming pools, advanced gym, dedicated BBQ areas, and cinema and games hall.',
                'order' => 1
            ],
            [
                'title_ar' => 'الخدمات المتاحة',
                'title_en' => 'Available Services',
                'content_ar' => 'يوفر المشروع خدمات متكاملة تشمل خدمة الكونسيرج على مدار الساعة، الأمن المتقدم، الصيانة الدورية، والتنظيف. كما يتضمن مراكز تجارية ومطاعم متنوعة لتلبية احتياجات السكان اليومية.',
                'content_en' => 'The project provides integrated services including 24/7 concierge service, advanced security, regular maintenance, and cleaning. It also includes commercial centers and diverse restaurants to meet residents\' daily needs.',
                'order' => 2
            ],
            [
                'title_ar' => 'المواصلات والوصولية',
                'title_en' => 'Transportation & Accessibility',
                'content_ar' => 'يتميز الموقع بسهولة الوصول إليه عبر شبكة مواصلات متطورة تربطه بجميع أنحاء أبوظبي. كما يقع بالقرب من المطار الدولي والموانئ البحرية، مما يجعله مثالي للاستثمار والسياحة.',
                'content_en' => 'The location is distinguished by easy access through an advanced transportation network that connects it to all parts of Abu Dhabi. It is also located near the international airport and seaports, making it ideal for investment and tourism.',
                'order' => 3
            ],
            [
                'title_ar' => 'المرافق الصحية والرياضية',
                'title_en' => 'Health & Sports Facilities',
                'content_ar' => 'يحتوي المشروع على مرافق صحية ورياضية متطورة تشمل مركز اللياقة البدنية، الساونا، مرافق العافية، ومنطقة الألعاب المائية. كما يوفر مساحات خضراء واسعة للاسترخاء والتمتع بالطبيعة.',
                'content_en' => 'The project includes advanced health and sports facilities including fitness center, sauna, wellness facilities, and splash pad. It also provides extensive green spaces for relaxation and enjoying nature.',
                'order' => 4
            ]
        ];

        foreach ($contentBlocks as $block) {
            ProjectContentBlock::create([
                'project_id' => $project->id,
                'title_ar' => $block['title_ar'],
                'title_en' => $block['title_en'],
                'content_ar' => $block['content_ar'],
                'content_en' => $block['content_en'],
                'order' => $block['order'],
                'is_active' => true
            ]);
        }

        $this->command->info("✅ Added project content blocks");
    }

    private function addProjectImages($project)
    {
        $imageTypes = [
            'exterior' => [
                'https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?w=1200&h=800&fit=crop&q=80',
                'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?w=1200&h=800&fit=crop&q=80',
                'https://images.unsplash.com/photo-1564013799919-ab600027ffc6?w=1200&h=800&fit=crop&q=80'
            ],
            'interior' => [
                'https://images.unsplash.com/photo-1570129477492-45c003edd2be?w=1200&h=800&fit=crop&q=80',
                'https://images.unsplash.com/photo-1558618047-3c8c76ca7d13?w=1200&h=800&fit=crop&q=80',
                'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?w=1200&h=800&fit=crop&q=80'
            ],
            'featured' => [
                'https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?w=1200&h=800&fit=crop&q=80',
                'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?w=1200&h=800&fit=crop&q=80'
            ]
        ];

        $projectsDir = public_path('projects/images');
        if (!file_exists($projectsDir)) {
            mkdir($projectsDir, 0755, true);
        }

        $order = 1;
        foreach ($imageTypes as $type => $urls) {
            foreach ($urls as $index => $url) {
                try {
                    $imageContent = Http::timeout(30)->get($url);
                    
                    if ($imageContent->successful()) {
                        $imageName = 'saadiyat_resort_' . $type . '_' . ($index + 1) . '_' . time() . '.jpg';
                        $imagePath = $projectsDir . '/' . $imageName;
                        file_put_contents($imagePath, $imageContent->body());
                        
                        // Create project image record
                        ProjectImage::create([
                            'project_id' => $project->id,
                            'type' => $type,
                            'image_path' => $imageName,
                            'is_featured' => $index === 0,
                            'order' => $order++,
                            'title_ar' => 'صورة ' . $type . ' ' . ($index + 1),
                            'title_en' => ucfirst($type) . ' Image ' . ($index + 1),
                            'description_ar' => 'صورة ' . $type . ' لمشروع سعديات ريزورت',
                            'description_en' => ucfirst($type) . ' image for Saadiyat Resort project'
                        ]);
                        
                        $this->command->info("✅ Downloaded and added {$type} image: {$imageName}");
                    }
                } catch (\Exception $e) {
                    $this->command->error("❌ Failed to download image for {$project->prj_title_en}: " . $e->getMessage());
                }
            }
        }
    }
}
