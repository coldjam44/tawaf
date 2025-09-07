<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Area;
use Illuminate\Support\Facades\Http;

class AbuDhabiAreasNewImagesSeeder extends Seeder
{
    public function run(): void
    {
        // Completely different image URLs for each area
        $areaImages = [
            'saadiyat-island' => 'https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?w=1200&h=800&fit=crop&q=80',
            'al-reem-island' => 'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?w=1200&h=800&fit=crop&q=80',
            'yas-island' => 'https://images.unsplash.com/photo-1564013799919-ab600027ffc6?w=1200&h=800&fit=crop&q=80',
            'fahid-island' => 'https://images.unsplash.com/photo-1570129477492-45c003edd2be?w=1200&h=800&fit=crop&q=80',
            'zayed-city' => 'https://images.unsplash.com/photo-1558618047-3c8c76ca7d13?w=1200&h=800&fit=crop&q=80',
            'masdar-city' => 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?w=1200&h=800&fit=crop&q=80',
            'al-jurf' => 'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?w=1200&h=800&fit=crop&q=80',
            'al-hudayriat-island' => 'https://images.unsplash.com/photo-1564013799919-ab600027ffc6?w=1200&h=800&fit=crop&q=80',
            'al-jubail-island' => 'https://images.unsplash.com/photo-1570129477492-45c003edd2be?w=1200&h=800&fit=crop&q=80',
            'al-shamkha' => 'https://images.unsplash.com/photo-1558618047-3c8c76ca7d13?w=1200&h=800&fit=crop&q=80',
            'al-maryah-island' => 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?w=1200&h=800&fit=crop&q=80',
            'ramhan-island' => 'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?w=1200&h=800&fit=crop&q=80'
        ];

        $areasDir = public_path('areas/images');
        if (!file_exists($areasDir)) {
            mkdir($areasDir, 0755, true);
        }

        foreach ($areaImages as $slug => $imageUrl) {
            $area = Area::where('slug', $slug)->first();
            if ($area) {
                try {
                    $imageContent = Http::timeout(30)->get($imageUrl);
                    
                    if ($imageContent->successful()) {
                        // Delete old image if exists
                        if ($area->main_image && file_exists($areasDir . '/' . $area->main_image)) {
                            unlink($areasDir . '/' . $area->main_image);
                        }
                        
                        $imageName = $slug . '_new_' . time() . '.jpg';
                        $imagePath = $areasDir . '/' . $imageName;
                        file_put_contents($imagePath, $imageContent->body());
                        
                        $area->update(['main_image' => $imageName]);
                        $this->command->info("🔄 Updated {$area->name_en} with NEW image: {$imageName}");
                    } else {
                        $this->command->error("❌ Failed to download NEW image for {$area->name_en}");
                    }
                } catch (\Exception $e) {
                    $this->command->error("❌ Error updating {$area->name_en}: " . $e->getMessage());
                }
            }
        }
        
        $this->command->info('🎉 NEW images seeder completed successfully!');
    }
}
