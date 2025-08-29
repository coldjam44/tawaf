<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Developer extends Model
{
    use HasFactory;

    // إضافة الحقول المسموح بها للـ mass assignment
    protected $fillable = [
        'name_en',
        'name_ar',
        'email',
        'image',
        'phone',
        'company_id',
    ];

    public function projects()
    {
        return $this->hasMany(Project::class, 'prj_developerId');
    }

    public function company()
    {
        return $this->belongsTo(RealEstateCompany::class, 'company_id');
    }
}
