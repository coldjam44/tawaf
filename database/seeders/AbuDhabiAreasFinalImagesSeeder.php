<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Area;
use Illuminate\Support\Facades\Http;

class AbuDhabiAreasFinalImagesSeeder extends Seeder
{
    public function run(): void
    {
        // High-quality images from reliable sources
        $areaImages = [
            'saadiyat-island' => 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?w=1200&h=800&fit=crop&q=80',
            'al-reem-island' => 'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?w=1200&h=800&fit=crop&q=80',
            'yas-island' => 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?w=1200&h=800&fit=crop&q=80',
            'fahid-island' => 'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?w=1200&h=800&fit=crop&q=80',
            'zayed-city' => 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?w=1200&h=800&fit=crop&q=80',
            'masdar-city' => 'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?w=1200&h=800&fit=crop&q=80',
            'al-jurf' => 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?w=1200&h=800&fit=crop&q=80',
            'al-hudayriat-island' => 'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?w=1200&h=800&fit=crop&q=80',
            'al-jubail-island' => 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?w=1200&h=800&fit=crop&q=80',
            'al-shamkha' => 'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?w=1200&h=800&fit=crop&q=80',
            'al-maryah-island' => 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?w=1200&h=800&fit=crop&q=80',
            'ramhan-island' => 'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?w=1200&h=800&fit=crop&q=80'
        ];

        $areasDir = public_path('areas/images');
        if (!file_exists($areasDir)) {
            mkdir($areasDir, 0755, true);
        }

        foreach ($areaImages as $slug => $imageUrl) {
            $area = Area::where('slug', $slug)->first();
            if ($area) {
                try {
                    $imageContent = Http::timeout(20)->get($imageUrl);
                    
                    if ($imageContent->successful()) {
                        $imageName = $slug . '_final_' . time() . '.jpg';
                        $imagePath = $areasDir . '/' . $imageName;
                        file_put_contents($imagePath, $imageContent->body());
                        
                        $area->update(['main_image' => $imageName]);
                        $this->command->info("✅ Updated {$area->name_en} with final image: {$imageName}");
                    } else {
                        $this->command->error("❌ Failed to download image for {$area->name_en}");
                    }
                } catch (\Exception $e) {
                    $this->command->error("❌ Error updating {$area->name_en}: " . $e->getMessage());
                }
            }
        }
        
        $this->command->info('🎉 Final images seeder completed successfully!');
    }
}
