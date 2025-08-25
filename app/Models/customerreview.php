<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class customerreview extends Model
{
    use HasFactory;
    public $timestamps = true;

    protected $fillable=['name','description','hotel_id','image','rate'];

    public function hotel()
    {
        return $this->belongsTo(hotel::class);
    }



}
