<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class place extends Model
{
    use HasFactory;
    public $timestamps = true;

    protected $fillable = ['name_en','name_ar'];

    public function findstays()
    {
        return $this->hasMany(findstay::class);
    }

   

}
