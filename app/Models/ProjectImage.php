<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'type',
        'image_path',
        'title_ar',
        'title_en',
        'description_ar',
        'description_en',
        'order',
        'is_featured'
    ];

    protected $casts = [
        'is_featured' => 'boolean',
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
     * Get localized description based on current locale
     */
    public function getLocalizedDescriptionAttribute()
    {
        $locale = app()->getLocale();
        return $this->{"description_{$locale}"} ?: $this->description_ar ?: $this->description_en;
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
     * Get description for specific locale
     */
    public function getDescription($locale = null)
    {
        $locale = $locale ?: app()->getLocale();
        return $this->{"description_{$locale}"} ?: $this->description_ar ?: $this->description_en;
    }

    /**
     * Get full image URL
     */
    public function getImageUrlAttribute()
    {
        return asset('projects/images/' . $this->image_path);
    }

    /**
     * Get type label
     */
    public function getTypeLabelAttribute()
    {
        $labels = [
            'interior' => trans('main_trans.interior'),
            'exterior' => trans('main_trans.exterior'),
            'floorplan' => trans('main_trans.floorplan'),
            'featured' => trans('main_trans.featured')
        ];
        
        return $labels[$this->type] ?? $this->type;
    }

    /**
     * Scope for featured images
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope for specific type
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }
}
