<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    use HasFactory;

    protected $fillable = [
        'background_image',
        'project_logo',
        'title_en',
        'title_ar',
        'button1_text_en',
        'button1_text_ar',
        'button1_link',
        'button2_text_en',
        'button2_text_ar',
        'button2_link',
        'features_en',
        'features_ar',
        'brochure_link',
        'order',
    ];

    protected $casts = [
        'features_en' => 'array',
        'features_ar' => 'array',
    ];
}
