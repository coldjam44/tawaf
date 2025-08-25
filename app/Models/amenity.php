<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class amenity extends Model
{
    use HasFactory;
    public $timestamps = true;

protected $fillable = ['name_ar','name_en','image'];

public function hotels()
{
    return $this->belongsToMany(Hotel::class, 'hotel_amenity');
}

}
