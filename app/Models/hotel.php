<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class hotel extends Model
{
    use HasFactory;
    public $timestamps = true;

    protected $fillable = [
        'name_ar', 'name_en', 'possition_ar', 'possition_en',
        'overview_en', 'overview_ar',
        'rate', 'image', 'location_map'
         
    ];
    public function amenities()
    {
        return $this->belongsToMany(amenity::class, 'hotel_amenity');
    }
    public function place()
    {
        return $this->belongsTo(place::class);
    }
    public function features()
    {
        return $this->hasMany(feature::class);
    }

    public function customerreviews()
    {
        return $this->hasMany(customerreview::class);
    }


    // public function avilablerooms(){
    //     return $this->hasMany(avilableroom::class);
    // }

    // داخل نموذج Hotel
public function availableRooms()
{
    return $this->hasMany(avilableroom::class);
}
  
  public function hotelinhomes()
{
    return $this->hasMany(hotelinhome::class);

}

  public function hotelinmadinas()
{
    return $this->hasMany(hotelinmadina::class);
}
public function hotelinmakkahs()
{
    return $this->hasMany(hotelinmakkah::class);
}
  
 public function pricings()
{
    return $this->hasMany(hotelpricing::class);
}



}
