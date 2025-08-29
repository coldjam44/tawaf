<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectAmenity extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'amenity_type',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Amenity types constants with icons
    const AMENITY_TYPES = [
        'infinity_pool' => [
            'ar' => 'مسبح لانهائي',
            'en' => 'Infinity Pool',
            'icon' => 'fas fa-swimming-pool'
        ],
        'concierge_services' => [
            'ar' => 'خدمات الكونسيرج',
            'en' => 'Concierge Services',
            'icon' => 'fas fa-concierge-bell'
        ],
        'retail_fnb' => [
            'ar' => 'التجزئة والمطاعم',
            'en' => 'Retail and F&B',
            'icon' => 'fas fa-shopping-bag'
        ],
        'bbq_area' => [
            'ar' => 'منطقة الشواء',
            'en' => 'BBQ Area',
            'icon' => 'fas fa-fire'
        ],
        'cinema_game_room' => [
            'ar' => 'سينما وغرفة الألعاب',
            'en' => 'Cinema and Game Room',
            'icon' => 'fas fa-gamepad'
        ],
        'gym' => [
            'ar' => 'صالة رياضية متطورة',
            'en' => 'State-of-the-art Gym',
            'icon' => 'fas fa-dumbbell'
        ],
        'wellness_facilities' => [
            'ar' => 'مرافق العافية',
            'en' => 'Wellness Facilities',
            'icon' => 'fas fa-spa'
        ],
        'splash_pad' => [
            'ar' => 'منطقة الرش',
            'en' => 'Splash Pad',
            'icon' => 'fas fa-tint'
        ],
        'sauna_wellness' => [
            'ar' => 'ساونا ومرافق العافية',
            'en' => 'Sauna & Wellness Facilities',
            'icon' => 'fas fa-hot-tub'
        ],
        'multipurpose_court' => [
            'ar' => 'ملعب متعدد الأغراض ومسار الجري',
            'en' => 'Multipurpose Court & Jogging Track',
            'icon' => 'fas fa-running'
        ]
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get amenity name for specific locale
     */
    public function getAmenityName($locale = null)
    {
        $locale = $locale ?: app()->getLocale();
        return self::AMENITY_TYPES[$this->amenity_type][$locale] ?? $this->amenity_type;
    }

    /**
     * Get amenity icon
     */
    public function getAmenityIconAttribute()
    {
        return self::AMENITY_TYPES[$this->amenity_type]['icon'] ?? 'fas fa-star';
    }

    /**
     * Get localized amenity name
     */
    public function getLocalizedAmenityNameAttribute()
    {
        return $this->getAmenityName();
    }

    /**
     * Scope for active amenities
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for specific amenity type
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('amenity_type', $type);
    }

    /**
     * Get all available amenity types
     */
    public static function getAvailableTypes()
    {
        return array_keys(self::AMENITY_TYPES);
    }

    /**
     * Get amenity types with full data
     */
    public static function getAmenityTypesData()
    {
        return self::AMENITY_TYPES;
    }
}
