<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Project;
use App\Models\Area;
use App\Models\Developer;
use App\Models\RealEstateCompany;
use Illuminate\Support\Facades\Http;

class SaadiyatIslandProjectsSeeder extends Seeder
{
    public function run(): void
    {
        // Get Saadiyat Island area
        $saadiyatArea = Area::where('slug', 'saadiyat-island')->first();
        if (!$saadiyatArea) {
            $this->command->error('Saadiyat Island area not found!');
            return;
        }

        // Get or create developers
        $developers = [
            'Aldar Properties' => [
                'name_en' => 'Aldar Properties',
                'name_ar' => 'ألدار العقارية',
                'email' => 'info@aldar.com',
                'phone' => '+971 2 810 5555'
            ],
            'Miral' => [
                'name_en' => 'Miral',
                'name_ar' => 'ميرال',
                'email' => 'info@miral.ae',
                'phone' => '+971 2 496 2000'
            ],
            'TDIC' => [
                'name_en' => 'Tourism Development & Investment Company',
                'name_ar' => 'شركة التطوير والاستثمار السياحي',
                'email' => 'info@tdic.ae',
                'phone' => '+971 2 406 2000'
            ]
        ];

        $developerIds = [];
        foreach ($developers as $key => $developerData) {
            $developer = Developer::firstOrCreate(
                ['name_en' => $developerData['name_en']],
                $developerData
            );
            $developerIds[$key] = $developer->id;
        }

        // Get or create real estate companies
        $companies = [
            'Aldar Real Estate' => [
                'company_name_en' => 'Aldar Real Estate',
                'company_name_ar' => 'ألدار العقارية',
                'contact_number' => '+971 2 810 5555',
                'short_description_en' => 'Leading real estate development company in Abu Dhabi',
                'short_description_ar' => 'شركة رائدة في التطوير العقاري في أبوظبي'
            ],
            'Miral Real Estate' => [
                'company_name_en' => 'Miral Real Estate',
                'company_name_ar' => 'ميرال العقارية',
                'contact_number' => '+971 2 496 2000',
                'short_description_en' => 'Premier real estate development company',
                'short_description_ar' => 'شركة رائدة في التطوير العقاري'
            ]
        ];

        $companyIds = [];
        foreach ($companies as $key => $companyData) {
            $company = RealEstateCompany::firstOrCreate(
                ['company_name_en' => $companyData['company_name_en']],
                $companyData
            );
            $companyIds[$key] = $company->id;
        }

        // Projects from Bayut.com for Saadiyat Island
        $projects = [
            [
                'prj_title_ar' => 'سعديات ريزورت',
                'prj_title_en' => 'Saadiyat Resort',
                'prj_description_ar' => 'مشروع سكني فاخر في جزيرة السعديات يتميز بإطلالات بحرية خلابة ومرافق عالمية المستوى.',
                'prj_description_en' => 'Luxury residential project on Saadiyat Island featuring stunning sea views and world-class amenities.',
                'developer_key' => 'Aldar Properties',
                'company_key' => 'Aldar Real Estate',
                'image_url' => 'https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?w=1200&h=800&fit=crop&q=80'
            ],
            [
                'prj_title_ar' => 'سعديات جروف',
                'prj_title_en' => 'Saadiyat Grove',
                'prj_description_ar' => 'مجتمع سكني متكامل في جزيرة السعديات يوفر أسلوب حياة راقي مع مرافق ترفيهية متنوعة.',
                'prj_description_en' => 'Integrated residential community on Saadiyat Island offering luxury lifestyle with diverse recreational facilities.',
                'developer_key' => 'Miral',
                'company_key' => 'Miral Real Estate',
                'image_url' => 'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?w=1200&h=800&fit=crop&q=80'
            ],
            [
                'prj_title_ar' => 'سعديات هيلز',
                'prj_title_en' => 'Saadiyat Hills',
                'prj_description_ar' => 'مشروع سكني فاخر يتميز بتصاميم معمارية مبتكرة ومرافق حديثة في قلب جزيرة السعديات.',
                'prj_description_en' => 'Luxury residential project featuring innovative architectural designs and modern facilities in the heart of Saadiyat Island.',
                'developer_key' => 'TDIC',
                'company_key' => 'Aldar Real Estate',
                'image_url' => 'https://images.unsplash.com/photo-1564013799919-ab600027ffc6?w=1200&h=800&fit=crop&q=80'
            ],
            [
                'prj_title_ar' => 'سعديات مارينا',
                'prj_title_en' => 'Saadiyat Marina',
                'prj_description_ar' => 'مشروع سكني بحري فاخر يوفر إطلالات مباشرة على البحر مع مرافق بحرية متطورة.',
                'prj_description_en' => 'Luxury waterfront residential project offering direct sea views with advanced marine facilities.',
                'developer_key' => 'Aldar Properties',
                'company_key' => 'Aldar Real Estate',
                'image_url' => 'https://images.unsplash.com/photo-1570129477492-45c003edd2be?w=1200&h=800&fit=crop&q=80'
            ],
            [
                'prj_title_ar' => 'سعديات فيو',
                'prj_title_en' => 'Saadiyat View',
                'prj_description_ar' => 'مشروع سكني يتميز بإطلالات بانورامية على جزيرة السعديات مع مرافق ترفيهية ورياضية متكاملة.',
                'prj_description_en' => 'Residential project featuring panoramic views of Saadiyat Island with integrated recreational and sports facilities.',
                'developer_key' => 'Miral',
                'company_key' => 'Miral Real Estate',
                'image_url' => 'https://images.unsplash.com/photo-1558618047-3c8c76ca7d13?w=1200&h=800&fit=crop&q=80'
            ]
        ];

        $projectsDir = public_path('projects/images');
        if (!file_exists($projectsDir)) {
            mkdir($projectsDir, 0755, true);
        }

        foreach ($projects as $projectData) {
            // Check if project already exists
            $existingProject = Project::where('prj_title_en', $projectData['prj_title_en'])->first();
            if ($existingProject) {
                $this->command->info("Project {$projectData['prj_title_en']} already exists, skipping...");
                continue;
            }

            try {
                // Skip image download for now due to permissions
                $imageName = null;

                // Create project
                $project = Project::create([
                    'prj_areaId' => $saadiyatArea->id,
                    'company_id' => $companyIds[$projectData['company_key']],
                    'prj_title_ar' => $projectData['prj_title_ar'],
                    'prj_title_en' => $projectData['prj_title_en'],
                    'prj_description_ar' => $projectData['prj_description_ar'],
                    'prj_description_en' => $projectData['prj_description_en'],
                    'prj_projectNumber' => 'SAAD-' . rand(1000, 9999),
                    'prj_MadhmounPermitNumber' => 'PERMIT-' . rand(10000, 99999),
                    'is_sent_to_bot' => false
                ]);

                // Skip project image creation for now

                $this->command->info("✅ Created project: {$projectData['prj_title_en']}");
                
            } catch (\Exception $e) {
                $this->command->error("❌ Error creating project {$projectData['prj_title_en']}: " . $e->getMessage());
            }
        }
        
        $this->command->info('🎉 Saadiyat Island Projects seeder completed successfully!');
    }
}
