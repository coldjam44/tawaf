<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Blog;
use App\Models\BlogImage;

class CleanupMissingBlogImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'blog:cleanup-missing-images';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up blog images that exist in database but missing from filesystem';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting cleanup of missing blog images...');
        
        $cleanedBlogs = 0;
        $cleanedImages = 0;
        
        // Clean up main images
        $blogs = Blog::whereNotNull('main_image')->get();
        foreach ($blogs as $blog) {
            $mainImagePath = public_path('blogsfiles/main-images/' . $blog->main_image);
            if (!file_exists($mainImagePath)) {
                $this->warn("Main image missing for blog ID {$blog->id}: {$blog->main_image}");
                $blog->main_image = null;
                $blog->save();
                $cleanedBlogs++;
            }
        }
        
        // Clean up additional images
        $blogImages = BlogImage::all();
        foreach ($blogImages as $image) {
            $imagePath = public_path('blogsfiles/images/' . $image->image_path);
            if (!file_exists($imagePath)) {
                $this->warn("Image missing for blog ID {$image->blog_id}: {$image->image_path}");
                $image->delete();
                $cleanedImages++;
            }
        }
        
        $this->info("Cleanup completed!");
        $this->info("Cleaned {$cleanedBlogs} blogs with missing main images");
        $this->info("Cleaned {$cleanedImages} missing additional images");
        
        return 0;
    }
}