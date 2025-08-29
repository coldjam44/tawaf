<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AboutUsSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'section_name',
        'title_ar',
        'title_en',
        'content_ar',
        'content_en',
        'main_image',
        'order_index',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the images for this section
     */
    public function images()
    {
        return $this->hasMany(AboutUsImage::class)->orderBy('order_index', 'asc');
    }

    /**
     * Get active images for this section
     */
    public function activeImages()
    {
        return $this->hasMany(AboutUsImage::class)->where('is_active', true)->orderBy('order_index', 'asc');
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
            return asset('about-us/main-images/' . $this->main_image);
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
     * Scope for active sections
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for ordering
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order_index', 'asc');
    }
}
