<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Award extends Model
{
    use HasFactory;

    protected $fillable = [
        'title_ar',
        'title_en',
        'description_ar',
        'description_en',
        'image',
        'year',
        'category',
        'order_index',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order_index' => 'integer'
    ];

    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('awards/' . $this->image);
        }
        return null;
    }

    public function getLocalizedTitleAttribute()
    {
        return app()->getLocale() === 'ar' ? $this->title_ar : $this->title_en;
    }

    public function getLocalizedDescriptionAttribute()
    {
        return app()->getLocale() === 'ar' ? $this->description_ar : $this->description_en;
    }
}
