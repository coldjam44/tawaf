<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class hotelinmakkah extends Model
{
    use HasFactory;
    public $timestamps = true;
    protected $fillable=['title_ar','title_en','description_ar','description_en'];

    public function hotels()
{
    return $this->belongsToMany(hotel::class, 'hotelinmakkah_hotel');
}
}
