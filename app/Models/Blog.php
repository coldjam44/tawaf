<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Blog extends Model
{
    use HasFactory;

    protected $fillable = [
        'title_ar',
        'title_en',
        'content_ar',
        'content_en',
        'main_image',
        'slug',
        'status',
        'order_index',
        'is_active',
        'published_at'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'published_at' => 'datetime',
    ];

    /**
     * Get the images for this blog
     */
    public function images()
    {
        return $this->hasMany(BlogImage::class)->orderBy('order_index', 'asc');
    }

    /**
     * Get active images for this blog
     */
    public function activeImages()
    {
        return $this->hasMany(BlogImage::class)->where('is_active', true)->orderBy('order_index', 'asc');
    }

    /**
     * Get localized title
     */
    public function getLocalizedTitleAttribute()
    {
        $locale = app()->getLocale();
        return $this->{"title_{$locale}"} ?: $this->title_ar ?: $this->title_en;
    }

    /**
     * Get localized content
     */
    public function getLocalizedContentAttribute()
    {
        $locale = app()->getLocale();
        return $this->{"content_{$locale}"} ?: $this->content_ar ?: $this->content_en;
    }

    /**
     * Get main image URL
     */
    public function getMainImageUrlAttribute()
    {
        if ($this->main_image) {
            return asset('uploads/blog/main-images/' . $this->main_image);
        }
        return null;
    }

    /**
     * Get title for specific locale
     */
    public function getTitle($locale = null)
    {
        $locale = $locale ?: app()->getLocale();
        return $this->{"title_{$locale}"} ?: $this->title_ar ?: $this->title_en;
    }

    /**
     * Get content for specific locale
     */
    public function getContent($locale = null)
    {
        $locale = $locale ?: app()->getLocale();
        return $this->{"content_{$locale}"} ?: $this->content_ar ?: $this->content_en;
    }

    /**
     * Generate slug from title
     */
    public function generateSlug()
    {
        $title = $this->title_en ?: $this->title_ar;
        $baseSlug = Str::slug($title);
        $slug = $baseSlug;
        $counter = 1;

        while (static::where('slug', $slug)->where('id', '!=', $this->id)->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    /**
     * Scope for active blogs
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for published blogs
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    /**
     * Scope for ordering
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order_index', 'asc')->orderBy('created_at', 'desc');
    }

    /**
     * Boot method to auto-generate slug
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($blog) {
            if (empty($blog->slug)) {
                $blog->slug = $blog->generateSlug();
            }
        });

        static::updating(function ($blog) {
            if ($blog->isDirty('title_ar') || $blog->isDirty('title_en')) {
                $blog->slug = $blog->generateSlug();
            }
        });
    }
}
