<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_en',
        'name_ar',
        'slug',
        'about_community_overview_ar',
        'about_community_overview_en',
        'rental_sales_trends_ar',
        'rental_sales_trends_en',
        'roi_ar',
        'roi_en',
        'things_to_do_perks_ar',
        'things_to_do_perks_en'
    ];

    public function projects()
    {
        return $this->hasMany(Project::class, 'prj_areaId');
    }
}
