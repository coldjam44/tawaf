<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_ar',
        'name_en', 
        'position_ar',
        'position_en',
        'message_ar',
        'message_en',
        'image_path'
    ];

}
