<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class review extends Model
{
    use HasFactory;
    public $timestamps = true;

    protected $fillable = ['name_en', 'name_ar', 'possition_en', 'possition_ar',
    'rate', 'comment_en', 'comment_ar', 'description_en', 'description_ar'];

}
