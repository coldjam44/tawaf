<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ramadanoffer extends Model
{
    use HasFactory;
    public $timestamps = true;

    protected $fillable = ['price','image',
    'number_of_night','hotelname_en','hotelname_ar','roomtype_en','roomtype_ar','title_ar','title_en','breakfast_price','suhoor_price'];

public function booknows()
{
    return $this->hasMany(booknow::class);
}

}
