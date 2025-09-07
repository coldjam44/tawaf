<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Area;
use Illuminate\Support\Facades\Http;

class AbuDhabiAreasBayutImagesSeeder extends Seeder
{
    public function run(): void
    {
        // Real images from Bayut.com for Abu Dhabi areas
        $areaImages = [
            'saadiyat-island' => 'https://www.bayut.com/static/images/areas/saadiyat-island-hero.jpg',
            'al-reem-island' => 'https://www.bayut.com/static/images/areas/al-reem-island-hero.jpg',
            'yas-island' => 'https://www.bayut.com/static/images/areas/yas-island-hero.jpg',
            'fahid-island' => 'https://www.bayut.com/static/images/areas/fahid-island-hero.jpg',
            'zayed-city' => 'https://www.bayut.com/static/images/areas/zayed-city-hero.jpg',
            'masdar-city' => 'https://www.bayut.com/static/images/areas/masdar-city-hero.jpg',
            'al-jurf' => 'https://www.bayut.com/static/images/areas/al-jurf-hero.jpg',
            'al-hudayriat-island' => 'https://www.bayut.com/static/images/areas/al-hudayriat-island-hero.jpg',
            'al-jubail-island' => 'https://www.bayut.com/static/images/areas/al-jubail-island-hero.jpg',
            'al-shamkha' => 'https://www.bayut.com/static/images/areas/al-shamkha-hero.jpg',
            'al-maryah-island' => 'https://www.bayut.com/static/images/areas/al-maryah-island-hero.jpg',
            'ramhan-island' => 'https://www.bayut.com/static/images/areas/ramhan-island-hero.jpg'
        ];

        // Alternative approach - use project images from Bayut
        $projectImages = [
            'saadiyat-island' => 'https://www.bayut.com/static/images/projects/saadiyat-island-projects.jpg',
            'al-reem-island' => 'https://www.bayut.com/static/images/projects/al-reem-island-projects.jpg',
            'yas-island' => 'https://www.bayut.com/static/images/projects/yas-island-projects.jpg',
            'fahid-island' => 'https://www.bayut.com/static/images/projects/fahid-island-projects.jpg',
            'zayed-city' => 'https://www.bayut.com/static/images/projects/zayed-city-projects.jpg',
            'masdar-city' => 'https://www.bayut.com/static/images/projects/masdar-city-projects.jpg',
            'al-jurf' => 'https://www.bayut.com/static/images/projects/al-jurf-projects.jpg',
            'al-hudayriat-island' => 'https://www.bayut.com/static/images/projects/al-hudayriat-island-projects.jpg',
            'al-jubail-island' => 'https://www.bayut.com/static/images/projects/al-jubail-island-projects.jpg',
            'al-shamkha' => 'https://www.bayut.com/static/images/projects/al-shamkha-projects.jpg',
            'al-maryah-island' => 'https://www.bayut.com/static/images/projects/al-maryah-island-projects.jpg',
            'ramhan-island' => 'https://www.bayut.com/static/images/projects/ramhan-island-projects.jpg'
        ];

        $areasDir = public_path('areas/images');
        if (!file_exists($areasDir)) {
            mkdir($areasDir, 0755, true);
        }

        foreach ($areaImages as $slug => $imageUrl) {
            $area = Area::where('slug', $slug)->first();
            if ($area) {
                try {
                    // Try primary image URL first
                    $imageContent = Http::timeout(15)
                        ->withHeaders([
                            'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36'
                        ])
                        ->get($imageUrl);
                    
                    if (!$imageContent->successful() && isset($projectImages[$slug])) {
                        // Try alternative project image URL
                        $imageContent = Http::timeout(15)
                            ->withHeaders([
                                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36'
                            ])
                            ->get($projectImages[$slug]);
                    }
                    
                    if ($imageContent->successful()) {
                        $imageName = $slug . '_bayut_' . time() . '.jpg';
                        $imagePath = $areasDir . '/' . $imageName;
                        file_put_contents($imagePath, $imageContent->body());
                        
                        $area->update(['main_image' => $imageName]);
                        $this->command->info("Updated {$area->name_en} with Bayut image: {$imageName}");
                    } else {
                        $this->command->error("Failed to download image for {$area->name_en} from Bayut");
                    }
                } catch (\Exception $e) {
                    $this->command->error("Error updating {$area->name_en}: " . $e->getMessage());
                }
            }
        }
        
        $this->command->info('Bayut images seeder completed!');
    }
}
