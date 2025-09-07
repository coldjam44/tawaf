<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectContentBlockImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'content_block_id',
        'image_path',
        'title_ar',
        'title_en',
        'description_ar',
        'description_en',
        'order',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function contentBlock()
    {
        return $this->belongsTo(ProjectContentBlock::class, 'content_block_id');
    }

    // Get full image URL
    public function getImageUrlAttribute()
    {
        return url('projects/content-blocks/' . $this->image_path);
    }
}
