<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class booknow extends Model
{
    use HasFactory;
    public $timestamps = true;

    protected $fillable = ['fullname','phonenumber','specialrequest','check_in_ar','check_in_en',
    'check_out_ar','check_out_en','totalprice','numberofroom','numberofadult',
    'numberofchild','age_child','room_id','offer_id'];

    public function room(){
        return $this->belongsTo(avilableroom::class);
    }
  
    public function offer(){
        return $this->belongsTo(ramadanoffer::class);
    }
}
