<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class hotelpricing extends Model
{
    use HasFactory;
    public $timestamps = true;
    protected $fillable=[
        'hotel_id',
        'start_date',
        'end_date',
        'price',
      'discount_price'
    ];
  
   public function hotel()
    {
        return $this->belongsTo(hotel::class);
    }
}
