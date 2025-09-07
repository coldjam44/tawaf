<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'prj_developerId',
        'prj_areaId',
        'company_id',
        'prj_title_ar',
        'prj_title_en',
        'prj_description_ar',
        'prj_description_en',
        'prj_brochurefile',
        'prj_adm',
        'prj_cn',
        'prj_projectNumber',
        'prj_MadhmounPermitNumber',
        'prj_floorplan',
        'is_sent_to_bot'
    ];

    protected $casts = [
        'is_sent_to_bot' => 'boolean',
    ];

    public function developer()
    {
        return $this->belongsTo(Developer::class, 'prj_developerId');
    }

    public function company()
    {
        return $this->belongsTo(RealEstateCompany::class, 'company_id');
    }

    public function area()
    {
        return $this->belongsTo(Area::class, 'prj_areaId');
    }

    public function projectDetails()
    {
        return $this->hasMany(ProjectDetail::class)->orderBy('order', 'asc');
    }

    public function projectImages()
    {
        return $this->hasMany(ProjectImage::class)->orderBy('order', 'asc');
    }

    public function descriptions()
    {
        return $this->hasMany(ProjectDescription::class)->orderBy('order_index', 'asc');
    }

    public function amenities()
    {
        return $this->hasMany(ProjectAmenity::class);
    }

    public function contentBlocks()
    {
        return $this->hasMany(ProjectContentBlock::class)->orderBy('order', 'asc');
    }

    /**
     * Get images by type
     */
    public function getImagesByType($type)
    {
        return $this->projectImages()->where('type', $type)->orderBy('order', 'asc')->get();
    }

    /**
     * Get featured images
     */
    public function getFeaturedImages()
    {
        return $this->projectImages()->where('is_featured', true)->orderBy('order', 'asc')->get();
    }

    /**
     * Get localized title based on current locale
     */
    public function getLocalizedTitleAttribute()
    {
        $locale = app()->getLocale();
        return $this->{"prj_title_{$locale}"} ?: $this->prj_title_ar ?: $this->prj_title_en;
    }

    /**
     * Get localized description based on current locale
     */
    public function getLocalizedDescriptionAttribute()
    {
        $locale = app()->getLocale();
        return $this->{"prj_description_{$locale}"} ?: $this->prj_description_ar ?: $this->prj_description_en;
    }

    /**
     * Get title for specific locale
     */
    public function getTitle($locale = null)
    {
        $locale = $locale ?: app()->getLocale();
        return $this->{"prj_title_{$locale}"} ?: $this->prj_title_ar ?: $this->prj_title_en;
    }

    /**
     * Get description for specific locale
     */
    public function getDescription($locale = null)
    {
        $locale = $locale ?: app()->getLocale();
        return $this->{"prj_description_{$locale}"} ?: $this->prj_description_ar ?: $this->prj_description_en;
    }
}
