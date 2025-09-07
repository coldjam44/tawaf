<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PropertyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->propertyid,
            'project' => [
                'id' => $this->whenLoaded('project', function() {
                    return $this->project->id;
                }),
                'title_ar' => $this->whenLoaded('project', function() {
                    return $this->project->prj_title_ar;
                }),
                'title_en' => $this->whenLoaded('project', function() {
                    return $this->project->prj_title_en;
                }),
                'description_ar' => $this->whenLoaded('project', function() {
                    return $this->project->prj_description_ar;
                }),
                'description_en' => $this->whenLoaded('project', function() {
                    return $this->project->prj_description_en;
                }),
                'main_image' => $this->when($this->project, function() {
                    if ($this->project->relationLoaded('projectImages')) {
                        $mainImage = $this->project->projectImages->where('is_featured', true)->first();
                        return $mainImage ? url('projects/images/' . $mainImage->image_path) : null;
                    }
                    return null;
                }),
                'area' => $this->whenLoaded('project.area', function() {
                    return app()->getLocale() === 'ar' ? $this->project->area->name_ar : $this->project->area->name_en;
                }),
                'company' => $this->whenLoaded('project.company', function() {
                    return [
                        'id' => $this->project->company->id,
                        'name' => app()->getLocale() === 'ar' ? $this->project->company->company_name_ar : $this->project->company->company_name_en,
                    ];
                }),
            ],
            'purpose' => $this->propertypurpose,
            'price' => $this->propertyprice,
            'rooms' => $this->propertyrooms,
            'bathrooms' => $this->propertybathrooms,
            'area' => $this->propertyarea,
            'quantity' => $this->propertyquantity,
            'location' => $this->propertyloaction,
            'payment_plan' => $this->whenLoaded('paymentPlan', function() {
                return [
                    'id' => $this->paymentPlan->id,
                    'name' => $this->paymentPlan->getLocalizedNameAttribute(),
                    'description' => $this->paymentPlan->getLocalizedDescriptionAttribute(),
                ];
            }),
            'employee' => $this->whenLoaded('employee', function() {
                return [
                    'id' => $this->employee->id,
                    'name' => app()->getLocale() === 'ar' ? $this->employee->name_ar : $this->employee->name_en,
                    'email' => $this->employee->email,
                    'phone' => $this->employee->phone,
                ];
            }),
            'handover_date' => $this->propertyhandover?->format('Y-m-d'),
            'features' => $this->propertyfeatures,
            'full_details_ar' => $this->propertyfulldetils_ar,
            'full_details_en' => $this->propertyfulldetils_en,
            'images' => $this->when($this->propertyimages, function() {
                return collect($this->propertyimages)->map(function($image) {
                    return [
                        'full_url' => url('storage/' . $image)
                    ];
                });
            }),
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}
