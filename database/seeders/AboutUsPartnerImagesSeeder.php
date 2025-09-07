<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AboutUsImage;
use Illuminate\Support\Facades\Storage;

class AboutUsPartnerImagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Partner information
        $partners = [
            [
                'name' => 'Microsoft',
                'alt_text' => 'Microsoft Partner',
                'color' => '#0078D4'
            ],
            [
                'name' => 'Google',
                'alt_text' => 'Google Partner',
                'color' => '#4285F4'
            ],
            [
                'name' => 'Amazon',
                'alt_text' => 'Amazon Partner',
                'color' => '#FF9900'
            ],
            [
                'name' => 'Apple',
                'alt_text' => 'Apple Partner',
                'color' => '#000000'
            ],
            [
                'name' => 'Facebook',
                'alt_text' => 'Facebook Partner',
                'color' => '#1877F2'
            ],
            [
                'name' => 'Netflix',
                'alt_text' => 'Netflix Partner',
                'color' => '#E50914'
            ]
        ];

        $sectionId = 18; // About Us section ID
        $uploadPath = public_path('about-us/images');
        
        // Create directory if it doesn't exist
        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        // Clear existing images for this section
        $existingImages = AboutUsImage::where('about_us_section_id', $sectionId)->get();
        foreach ($existingImages as $image) {
            $imagePath = public_path('about-us/images/' . $image->image_path);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
            $image->delete();
        }

        // Create simple placeholder images
        foreach ($partners as $index => $partner) {
            try {
                // Generate filename
                $filename = 'partner' . ($index + 1) . '.jpg';
                $filePath = $uploadPath . '/' . $filename;
                
                // Create a simple colored rectangle image
                $this->createPlaceholderImage($filePath, $partner['name'], $partner['color']);
                
                // Create database record
                AboutUsImage::create([
                    'about_us_section_id' => $sectionId,
                    'image_path' => $filename,
                    'alt_text_ar' => $partner['alt_text'],
                    'alt_text_en' => $partner['alt_text'],
                    'caption_ar' => $partner['name'],
                    'caption_en' => $partner['name'],
                    'order_index' => $index,
                    'is_active' => true
                ]);
                
                $this->command->info("Created placeholder image: {$partner['name']} as {$filename}");
                
            } catch (\Exception $e) {
                $this->command->error("Error creating image for {$partner['name']}: " . $e->getMessage());
            }
        }

        $this->command->info('Partner images seeder completed!');
    }

    /**
     * Create a simple placeholder image
     */
    private function createPlaceholderImage($filePath, $text, $backgroundColor)
    {
        // Create a 200x100 image
        $width = 200;
        $height = 100;
        
        // Create image resource
        $image = imagecreate($width, $height);
        
        // Convert hex color to RGB
        $bgColor = $this->hexToRgb($backgroundColor);
        $bg = imagecolorallocate($image, $bgColor['r'], $bgColor['g'], $bgColor['b']);
        
        // White text color
        $textColor = imagecolorallocate($image, 255, 255, 255);
        
        // Fill background
        imagefill($image, 0, 0, $bg);
        
        // Add text (centered)
        $fontSize = 3;
        $textWidth = imagefontwidth($fontSize) * strlen($text);
        $textHeight = imagefontheight($fontSize);
        $x = ($width - $textWidth) / 2;
        $y = ($height - $textHeight) / 2;
        
        imagestring($image, $fontSize, $x, $y, $text, $textColor);
        
        // Save as JPEG
        imagejpeg($image, $filePath, 90);
        
        // Clean up
        imagedestroy($image);
    }

    /**
     * Convert hex color to RGB
     */
    private function hexToRgb($hex)
    {
        $hex = ltrim($hex, '#');
        return [
            'r' => hexdec(substr($hex, 0, 2)),
            'g' => hexdec(substr($hex, 2, 2)),
            'b' => hexdec(substr($hex, 4, 2))
        ];
    }
}
