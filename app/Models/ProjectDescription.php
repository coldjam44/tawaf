<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectDescription extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'section_type',
        'title_ar',
        'title_en',
        'content_ar',
        'content_en',
        'location_image',
        'google_location',
        'location_address_ar',
        'location_address_en',
        'order_index',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Section types constants
    const SECTION_TYPES = [
        'about' => 'About Project',
        'architecture' => 'Architecture & Design',
        'why_choose' => 'Why Choose This Project',
        'location' => 'Location & Connectivity',
        'investment' => 'Investment Benefits',
        'location_map' => 'Location Map'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get localized title based on current locale
     */
    public function getLocalizedTitleAttribute()
    {
        $locale = app()->getLocale();
        return $this->{"title_{$locale}"} ?: $this->title_ar ?: $this->title_en;
    }

    /**
     * Get localized content based on current locale
     */
    public function getLocalizedContentAttribute()
    {
        $locale = app()->getLocale();
        return $this->{"content_{$locale}"} ?: $this->content_ar ?: $this->content_en;
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
     * Get section type label
     */
    public function getSectionTypeLabelAttribute()
    {
        return self::SECTION_TYPES[$this->section_type] ?? $this->section_type;
    }

    /**
     * Scope for active descriptions
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for specific section type
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('section_type', $type);
    }

    /**
     * Get location image URL
     */
    public function getLocationImageUrlAttribute()
    {
        if ($this->location_image) {
            return asset('projects/location-images/' . $this->location_image);
        }
        return null;
    }

    /**
     * Get localized address
     */
    public function getLocalizedAddressAttribute()
    {
        $locale = app()->getLocale();
        return $this->{"location_address_{$locale}"} ?: $this->location_address_ar ?: $this->location_address_en;
    }

    /**
     * Get address for specific locale
     */
    public function getAddress($locale = null)
    {
        $locale = $locale ?: app()->getLocale();
        return $this->{"location_address_{$locale}"} ?: $this->location_address_ar ?: $this->location_address_en;
    }
}
