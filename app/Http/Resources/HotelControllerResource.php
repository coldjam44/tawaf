<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HotelControllerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
    return [
        'id' => $this->id,
        'name_en' => $this->name_en,
        'name_ar' => $this->name_ar,
        'possition_ar' => $this->possition_ar,
        'possition_en' => $this->possition_en,
        'overview_en' => $this->overview_en,
        'overview_ar' => $this->overview_ar,
        //'feature_ar' => $this->feature_ar,
        //'feature_en' => $this->feature_en,
        'rate' => $this->rate,
        'price' => $this->price,
        'image' => json_decode($this->image),
        'x_access' => $this->x_access,
        'y_access' => $this->y_access,
        'privacy_ar' => $this->privacy_ar,
        'privacy_en' => $this->privacy_en,
        'amenity_ids' => json_decode($this->amenity_ids),
    ];
    }
}
