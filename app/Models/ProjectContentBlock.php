<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectContentBlock extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'title_ar',
        'title_en',
        'content_ar',
        'content_en',
        'order',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    /**
     * Get the project that owns the content block.
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function images()
    {
        return $this->hasMany(ProjectContentBlockImage::class, 'content_block_id')->orderBy('order', 'asc');
    }
}
