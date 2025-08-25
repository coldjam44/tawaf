<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AvilableroomControllerResource extends JsonResource
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
           'availableroom_type_en' => $this->availableroom_type_en,
           'availableroom_type_ar' => $this->availableroom_type_ar,
           'availableroom_price' => $this->availableroom_price,
           'availableroom_image' => json_decode($this->availableroom_image),

       ];
    }
}
