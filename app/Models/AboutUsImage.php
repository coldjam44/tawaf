<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AboutUsImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'about_us_section_id',
        'image_path',
        'alt_text_ar',
        'alt_text_en',
        'caption_ar',
        'caption_en',
        'order_index',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the section that owns this image
     */
    public function section()
    {
        return $this->belongsTo(AboutUsSection::class, 'about_us_section_id');
    }

    /**
     * Get image URL
     */
    public function getImageUrlAttribute()
    {
        return asset('uploads/aboutus/images/' . $this->image_path);
    }

    /**
     * Get localized alt text
     */
    public function getLocalizedAltTextAttribute()
    {
        $locale = app()->getLocale();
        return $this->{"alt_text_{$locale}"} ?: $this->alt_text_ar ?: $this->alt_text_en;
    }

    /**
     * Get localized caption
     */
    public function getLocalizedCaptionAttribute()
    {
        $locale = app()->getLocale();
        return $this->{"caption_{$locale}"} ?: $this->caption_ar ?: $this->caption_en;
    }

    /**
     * Get alt text for specific locale
     */
    public function getAltText($locale = null)
    {
        $locale = $locale ?: app()->getLocale();
        return $this->{"alt_text_{$locale}"} ?: $this->alt_text_ar ?: $this->alt_text_en;
    }

    /**
     * Get caption for specific locale
     */
    public function getCaption($locale = null)
    {
        $locale = $locale ?: app()->getLocale();
        return $this->{"caption_{$locale}"} ?: $this->caption_ar ?: $this->caption_en;
    }

    /**
     * Scope for active images
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
