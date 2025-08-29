<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpertTeam extends Model
{
    use HasFactory;

    protected $table = 'expert_team';

    protected $fillable = [
        'name_ar',
        'name_en',
        'position_ar',
        'position_en',
        'image',
        'order_index'
    ];

    protected $casts = [
        'order_index' => 'integer'
    ];

    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('expert-team/' . $this->image);
        }
        return null;
    }

    public function getLocalizedNameAttribute()
    {
        return app()->getLocale() === 'ar' ? $this->name_ar : $this->name_en;
    }

    public function getLocalizedPositionAttribute()
    {
        return app()->getLocale() === 'ar' ? $this->position_ar : $this->position_en;
    }
}
