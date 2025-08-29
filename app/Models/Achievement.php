<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Achievement extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_ar',
        'name_en',
        'image',
        'order_index',
    ];

    protected $casts = [
    ];

    public function getLocalizedNameAttribute(): string
    {
        return app()->getLocale() === 'ar' ? ($this->name_ar ?? '') : ($this->name_en ?? '');
    }

    public function getImageUrlAttribute(): ?string
    {
        return $this->image ? asset('achievements/' . $this->image) : null;
    }
}


