<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'title_ar', 'title_en', 'description_ar', 'description_en', 'image', 'contact_phone', 'request_service'
    ];

    protected $casts = [
        'request_service' => 'boolean',
    ];

    public function getImageUrlAttribute(): ?string
    {
        return $this->image ? asset('services/' . $this->image) : null;
    }
}


