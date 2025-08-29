<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Project;
use App\Models\ProjectDescription;

class LocationMapSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $projects = Project::all();

        if ($projects->isEmpty()) {
            $this->command->error('Please run ProjectsSeeder first!');
            return;
        }

        foreach ($projects as $project) {
            $this->addLocationMapDescription($project);
        }

        $this->command->info('✅ Location map descriptions have been created successfully!');
        $this->command->info('📍 Each project now has a location map description');
    }

    private function addLocationMapDescription($project)
    {
        // Check if location_map description already exists
        $existingLocationMap = ProjectDescription::where('project_id', $project->id)
                                                ->where('section_type', 'location_map')
                                                ->first();

        if ($existingLocationMap) {
            return; // Skip if already exists
        }

        $projectName = $project->prj_title_en;
        $areaName = $project->area ? $project->area->name_en : 'Abu Dhabi';

        ProjectDescription::create([
            'project_id' => $project->id,
            'section_type' => 'location_map',
            'title_ar' => 'خريطة الموقع: ' . $project->prj_title_ar,
            'title_en' => 'Location Map: ' . $projectName,
            'content_ar' => $this->getLocationContentAr($projectName, $areaName),
            'content_en' => $this->getLocationContentEn($projectName, $areaName),
            'location_image' => null, // Will be uploaded manually
            'google_location' => 'https://maps.google.com/?q=' . urlencode($projectName . ' ' . $areaName),
            'location_address_ar' => $project->prj_title_ar . '، ' . ($project->area ? $project->area->name_ar : 'أبو ظبي'),
            'location_address_en' => $projectName . ', ' . $areaName,
            'order_index' => 6, // After other descriptions
            'is_active' => true
        ]);
    }

    private function getLocationContentAr($projectName, $areaName)
    {
        return "<p>يتميز <strong>{$projectName}</strong> بموقع استراتيجي مثالي في منطقة {$areaName}، مما يوفر سهولة الوصول إلى جميع الخدمات والمرافق الأساسية.</p>

<p>يقع المشروع في قلب منطقة حيوية ومتطورة، حيث يوفر:</p>

<ul>
<li><strong>سهولة الوصول:</strong> قرب من الطرق الرئيسية والمواصلات العامة</li>
<li><strong>المرافق القريبة:</strong> مراكز تجارية ومطاعم ومستشفيات ومدارس</li>
<li><strong>الخدمات الأساسية:</strong> بنوك ومكاتب بريد ومراكز خدمات</li>
<li><strong>الترفيه:</strong> حدائق ومراكز ترفيهية ورياضية</li>
</ul>

<p>يتميز الموقع بكونه في منطقة آمنة وهادئة، مع الحفاظ على سهولة الوصول إلى جميع الخدمات التي يحتاجها السكان.</p>";
    }

    private function getLocationContentEn($projectName, $areaName)
    {
        return "<p><strong>{$projectName}</strong> boasts a highly strategic location within the vibrant {$areaName}, providing exceptional connectivity to key Abu Dhabi landmarks.</p>

<p>Nestled along serene waterfront promenades and picturesque marina views, residents enjoy effortless access to major transportation links, reputable schools, premium healthcare facilities, and upscale shopping and dining venues.</p>

<p>Its proximity to Abu Dhabi's city center, Yas Island, Saadiyat Island, and Zayed International Airport ensures that both leisure and business destinations are comfortably reachable, making {$projectName} an ideal blend of convenience, luxury, and accessibility.</p>

<p>The location offers:</p>

<ul>
<li><strong>Easy Access:</strong> Close to main roads and public transportation</li>
<li><strong>Nearby Facilities:</strong> Shopping centers, restaurants, hospitals, and schools</li>
<li><strong>Essential Services:</strong> Banks, post offices, and service centers</li>
<li><strong>Entertainment:</strong> Parks, entertainment and sports centers</li>
</ul>

<p>The location is characterized by being in a safe and quiet area, while maintaining easy access to all the services that residents need.</p>";
    }
}
