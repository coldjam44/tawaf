<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'detail_ar',
        'detail_en',
        'detail_value_ar',
        'detail_value_en',
        'order'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get localized detail based on current locale
     */
    public function getLocalizedDetailAttribute()
    {
        $locale = app()->getLocale();
        return $this->{"detail_{$locale}"} ?: $this->detail_ar ?: $this->detail_en;
    }

    /**
     * Get localized detail value based on current locale
     */
    public function getLocalizedDetailValueAttribute()
    {
        $locale = app()->getLocale();
        return $this->{"detail_value_{$locale}"} ?: $this->detail_value_ar ?: $this->detail_value_en;
    }

    /**
     * Get detail for specific locale
     */
    public function getDetail($locale = null)
    {
        $locale = $locale ?: app()->getLocale();
        return $this->{"detail_{$locale}"} ?: $this->detail_ar ?: $this->detail_en;
    }

    /**
     * Get detail value for specific locale
     */
    public function getDetailValue($locale = null)
    {
        $locale = $locale ?: app()->getLocale();
        return $this->{"detail_value_{$locale}"} ?: $this->detail_value_ar ?: $this->detail_value_en;
    }
}
