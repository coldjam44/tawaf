<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class feature extends Model
{
    use HasFactory;
    public $timestamps = true;
    protected $fillable = ['name_ar','name_en','hotel_id'];

    public function hotel()
    {
        return $this->belongsTo(hotel::class);
    }
}
