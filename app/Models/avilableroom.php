<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class avilableroom extends Model
{
    use HasFactory;
    public $timestamps = true;
protected $fillable = ['availableroom_type_en','availableroom_type_ar','availableroom_image','availableroom_price','hotel_id'];


public function hotel()
{
    return $this->belongsTo(hotel::class);
}
}
