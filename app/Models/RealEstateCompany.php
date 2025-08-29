<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RealEstateCompany extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_logo',
        'company_name_ar',
        'company_name_en',
        'short_description_ar',
        'short_description_en',
        'about_company_ar',
        'about_company_en',
        'company_images',
        'contact_number',
        'features_ar',
        'features_en',
        'properties_communities_ar',
        'properties_communities_en',
        'adm_number',
        'cn_number',
    ];

    protected $casts = [
        'company_images' => 'array',
    ];

    public function getCompanyNameAttribute()
    {
        return app()->getLocale() === 'ar' ? $this->company_name_ar : $this->company_name_en;
    }

    public function getShortDescriptionAttribute()
    {
        return app()->getLocale() === 'ar' ? $this->short_description_ar : $this->short_description_en;
    }

    public function getAboutCompanyAttribute()
    {
        return app()->getLocale() === 'ar' ? $this->about_company_ar : $this->about_company_en;
    }

    public function getFeaturesAttribute()
    {
        return app()->getLocale() === 'ar' ? $this->features_ar : $this->features_en;
    }

    public function getPropertiesCommunitiesAttribute()
    {
        return app()->getLocale() === 'ar' ? $this->properties_communities_ar : $this->properties_communities_en;
    }

    public function getLogoUrlAttribute()
    {
        return $this->company_logo ? asset('real-estate-companies/' . $this->company_logo) : null;
    }

    public function getCompanyImagesUrlsAttribute()
    {
        if (!$this->company_images) {
            return [];
        }

        return array_map(function($image) {
            return asset('real-estate-companies/' . $image);
        }, $this->company_images);
    }

    public function developers()
    {
        return $this->hasMany(Developer::class, 'company_id');
    }

    public function projects()
    {
        return $this->hasMany(Project::class, 'company_id');
    }
}
