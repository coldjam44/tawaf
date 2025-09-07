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
use Illuminate\Support\Facades\Http;

class SaadiyatIslandProjectsFullDetailsSeeder extends Seeder
{
    public function run(): void
    {
        // Get Saadiyat Island projects
        $saadiyatArea = Area::where('slug', 'saadiyat-island')->first();
        if (!$saadiyatArea) {
            $this->command->error('Saadiyat Island area not found!');
            return;
        }

        $projects = Project::where('prj_areaId', $saadiyatArea->id)->get();
        
        if ($projects->isEmpty()) {
            $this->command->error('No projects found in Saadiyat Island!');
            return;
        }

        foreach ($projects as $project) {
            $this->command->info("Processing project: {$project->prj_title_en}");
            
            // Add project details
            $this->addProjectDetails($project);
            
            // Add project descriptions
            $this->addProjectDescriptions($project);
            
            // Add project amenities
            $this->addProjectAmenities($project);
            
            // Add project content blocks
            $this->addProjectContentBlocks($project);
            
            // Add project images
            $this->addProjectImages($project);
        }
        
        $this->command->info('🎉 Saadiyat Island Projects Full Details seeder completed successfully!');
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
                'detail_value_ar' => 'شقق وفلل',
                'detail_value_en' => 'Apartments & Villas',
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
                'detail_value_ar' => '800 - 2,500 قدم مربع',
                'detail_value_en' => '800 - 2,500 sq ft',
                'order' => 8
            ]
        ];

        foreach ($details as $detail) {
            ProjectDetail::firstOrCreate([
                'project_id' => $project->id,
                'detail_en' => $detail['detail_en']
            ], [
                'detail_ar' => $detail['detail_ar'],
                'detail_value_ar' => $detail['detail_value_ar'],
                'detail_value_en' => $detail['detail_value_en'],
                'order' => $detail['order']
            ]);
        }
    }

    private function addProjectDescriptions($project)
    {
        $descriptions = [
            [
                'section_type' => 'about',
                'title_ar' => 'حول المشروع',
                'title_en' => 'About the Project',
                'content_ar' => 'مشروع سكني فاخر في جزيرة السعديات يتميز بإطلالات بحرية خلابة ومرافق عالمية المستوى. يوفر المشروع أسلوب حياة راقي مع تصاميم معمارية مبتكرة ومرافق متطورة تلائم أنماط الحياة الحديثة.',
                'content_en' => 'A luxury residential project on Saadiyat Island featuring stunning sea views and world-class amenities. The project offers an elegant lifestyle with innovative architectural designs and modern facilities that suit contemporary living patterns.',
                'order_index' => 1
            ],
            [
                'section_type' => 'architecture',
                'title_ar' => 'التصميم المعماري',
                'title_en' => 'Architecture',
                'content_ar' => 'يتميز المشروع بتصاميم معمارية عصرية تجمع بين الأناقة والوظائفية. تم تصميم الوحدات السكنية بعناية فائقة لتوفير أقصى درجات الراحة والخصوصية مع الاستفادة من الإطلالات البحرية الخلابة.',
                'content_en' => 'The project features contemporary architectural designs that combine elegance and functionality. Residential units are carefully designed to provide maximum comfort and privacy while taking advantage of stunning sea views.',
                'order_index' => 2
            ],
            [
                'section_type' => 'why_choose',
                'title_ar' => 'لماذا تختار هذا المشروع؟',
                'title_en' => 'Why Choose This Project?',
                'content_ar' => 'موقع استراتيجي في جزيرة السعديات، مرافق ترفيهية متكاملة، تصاميم عصرية، خطط دفع مرنة، ومطور موثوق مع سجل حافل في التطوير العقاري الفاخر.',
                'content_en' => 'Strategic location on Saadiyat Island, integrated recreational facilities, contemporary designs, flexible payment plans, and a trusted developer with a proven track record in luxury real estate development.',
                'order_index' => 3
            ],
            [
                'section_type' => 'location',
                'title_ar' => 'الموقع',
                'title_en' => 'Location',
                'content_ar' => 'يقع المشروع في جزيرة السعديات، أحد أكثر المناطق حيوية في أبوظبي. يتميز الموقع بقربه من الشواطئ والمرافق الترفيهية والمؤسسات التعليمية والطبية.',
                'content_en' => 'The project is located on Saadiyat Island, one of the most vibrant areas in Abu Dhabi. The location is distinguished by its proximity to beaches, recreational facilities, educational and medical institutions.',
                'order_index' => 4
            ],
            [
                'section_type' => 'investment',
                'title_ar' => 'الاستثمار',
                'title_en' => 'Investment',
                'content_ar' => 'جزيرة السعديات تعد من أفضل المناطق للاستثمار العقاري في أبوظبي مع إمكانية تحقيق عوائد استثمارية ممتازة. المشروع يوفر فرصة استثمارية فريدة في موقع متميز.',
                'content_en' => 'Saadiyat Island is one of the best areas for real estate investment in Abu Dhabi with the potential for excellent investment returns. The project provides a unique investment opportunity in a prime location.',
                'order_index' => 5
            ]
        ];

        foreach ($descriptions as $description) {
            ProjectDescription::firstOrCreate([
                'project_id' => $project->id,
                'section_type' => $description['section_type']
            ], [
                'title_ar' => $description['title_ar'],
                'title_en' => $description['title_en'],
                'content_ar' => $description['content_ar'],
                'content_en' => $description['content_en'],
                'order_index' => $description['order_index']
            ]);
        }
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
            ProjectAmenity::firstOrCreate([
                'project_id' => $project->id,
                'amenity_type' => $amenity
            ], [
                'is_active' => true
            ]);
        }
    }

    private function addProjectContentBlocks($project)
    {
        $contentBlocks = [
            [
                'title_ar' => 'المرافق الترفيهية',
                'title_en' => 'Recreational Facilities',
                'content_ar' => 'يضم المشروع مجموعة متنوعة من المرافق الترفيهية التي تلبي جميع احتياجات السكان، بما في ذلك حمامات السباحة، صالة الألعاب الرياضية، مناطق الشواء، وصالة السينما.',
                'content_en' => 'The project includes a variety of recreational facilities that meet all residents\' needs, including swimming pools, gym, BBQ areas, and cinema hall.',
                'order' => 1
            ],
            [
                'title_ar' => 'الخدمات المتاحة',
                'title_en' => 'Available Services',
                'content_ar' => 'يوفر المشروع خدمات متكاملة تشمل خدمة الكونسيرج، الأمن على مدار الساعة، الصيانة، والتنظيف. كما يتضمن مراكز تجارية ومطاعم لتلبية احتياجات السكان.',
                'content_en' => 'The project provides integrated services including concierge service, 24/7 security, maintenance, and cleaning. It also includes commercial centers and restaurants to meet residents\' needs.',
                'order' => 2
            ],
            [
                'title_ar' => 'المواصلات والوصولية',
                'title_en' => 'Transportation & Accessibility',
                'content_ar' => 'يتميز الموقع بسهولة الوصول إليه عبر شبكة مواصلات متطورة تربطه بجميع أنحاء أبوظبي. كما يقع بالقرب من المطار والموانئ البحرية.',
                'content_en' => 'The location is distinguished by easy access through an advanced transportation network that connects it to all parts of Abu Dhabi. It is also located near the airport and seaports.',
                'order' => 3
            ]
        ];

        foreach ($contentBlocks as $block) {
            ProjectContentBlock::firstOrCreate([
                'project_id' => $project->id,
                'title_en' => $block['title_en']
            ], [
                'title_ar' => $block['title_ar'],
                'content_ar' => $block['content_ar'],
                'content_en' => $block['content_en'],
                'order' => $block['order'],
                'is_active' => true
            ]);
        }
    }

    private function addProjectImages($project)
    {
        $imageTypes = [
            'exterior' => [
                'https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?w=1200&h=800&fit=crop&q=80',
                'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?w=1200&h=800&fit=crop&q=80'
            ],
            'interior' => [
                'https://images.unsplash.com/photo-1564013799919-ab600027ffc6?w=1200&h=800&fit=crop&q=80',
                'https://images.unsplash.com/photo-1570129477492-45c003edd2be?w=1200&h=800&fit=crop&q=80'
            ],
            'featured' => [
                'https://images.unsplash.com/photo-1558618047-3c8c76ca7d13?w=1200&h=800&fit=crop&q=80',
                'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?w=1200&h=800&fit=crop&q=80'
            ]
        ];

        $order = 1;
        foreach ($imageTypes as $type => $urls) {
            foreach ($urls as $index => $url) {
                try {
                    // Skip actual image download for now, just create records
                    $imageName = strtolower(str_replace(' ', '_', $project->prj_title_en)) . '_' . $type . '_' . ($index + 1) . '_' . time() . '.jpg';
                    
                    // Create project image record
                    ProjectImage::firstOrCreate([
                        'project_id' => $project->id,
                        'image_path' => $imageName
                    ], [
                        'type' => $type,
                        'is_featured' => $index === 0,
                        'order' => $order++,
                        'title_ar' => 'صورة ' . $type . ' ' . ($index + 1),
                        'title_en' => ucfirst($type) . ' Image ' . ($index + 1),
                        'description_ar' => 'صورة ' . $type . ' للمشروع ' . $project->prj_title_ar,
                        'description_en' => ucfirst($type) . ' image for ' . $project->prj_title_en
                    ]);
                    
                    $this->command->info("✅ Added {$type} image for {$project->prj_title_en}");
                } catch (\Exception $e) {
                    $this->command->error("Failed to add image for {$project->prj_title_en}: " . $e->getMessage());
                }
            }
        }
    }
}
