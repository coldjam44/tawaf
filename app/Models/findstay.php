<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class findstay extends Model
{
    use HasFactory;
    public $timestamps = true;

    protected $fillable = ['check_in_ar', 'check_in_en', 'check_out_ar', 'check_out_en',
        'numberofroom', 'numberofadult', 'numberofchild', 'place_id','age_child'];

public function place()
{
    return $this->belongsTo(place::class);

}
}
