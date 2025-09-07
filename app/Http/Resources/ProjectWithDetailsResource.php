<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectWithDetailsResource extends JsonResource
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
            'title' => [
                'ar' => $this->prj_title_ar,
                'en' => $this->prj_title_en,
                'current' => $this->getLocalizedTitleAttribute()
            ],
            'description' => [
                'ar' => $this->prj_description_ar,
                'en' => $this->prj_description_en,
                'current' => $this->getLocalizedDescriptionAttribute()
            ],
            'area' => $this->whenLoaded('area', function () {
                return [
                    'id' => $this->area->id,
                    'name' => [
                        'ar' => $this->area->name_ar,
                        'en' => $this->area->name_en
                    ],
                    'slug' => $this->area->slug
                ];
            }),
            'developer' => $this->whenLoaded('developer', function () {
                return [
                    'id' => $this->developer->id,
                    'name' => [
                        'ar' => $this->developer->name_ar,
                        'en' => $this->developer->name_en
                    ],
                    'image' => $this->developer->image ? asset('developers/' . $this->developer->image) : null,
                    'email' => $this->developer->email,
                    'phone' => $this->developer->phone
                ];
            }),
            'company' => $this->whenLoaded('company', function () {
                return [
                    'id' => $this->company->id,
                    'name' => [
                        'ar' => $this->company->company_name_ar,
                        'en' => $this->company->company_name_en
                    ],
                    'logo' => $this->company->company_logo ? asset('real-estate-companies/' . $this->company->company_logo) : null,
                    'company_images' => $this->company->company_images ? $this->company->getCompanyImagesUrlsAttribute() : [],
                    'contact_number' => $this->company->contact_number
                ];
            }),
            'project_numbers' => [
                'adm' => $this->prj_adm,
                'cn' => $this->prj_cn,
                'project_number' => $this->prj_projectNumber,
                'madhmoun_permit_number' => $this->prj_MadhmounPermitNumber
            ],
            'files' => [
                'brochure' => $this->prj_brochurefile ? asset('projects/brochures/' . $this->prj_brochurefile) : null,
                'floorplan' => $this->prj_floorplan ? asset('projects/floorplans/' . $this->prj_floorplan) : null
            ],
            'project_details' => $this->whenLoaded('projectDetails', function () {
                return $this->projectDetails->map(function ($detail) {
                    return [
                        'id' => $detail->id,
                        'detail' => [
                            'ar' => $detail->detail_ar,
                            'en' => $detail->detail_en
                        ],
                        'detail_value' => [
                            'ar' => $detail->detail_value_ar,
                            'en' => $detail->detail_value_en
                        ],
                        'order' => $detail->order
                    ];
                });
            }),
            'images' => $this->whenLoaded('projectImages', function () {
                $images = $this->projectImages->groupBy('type');
                
                return [
                    'interior' => $images->get('interior', collect())->map(function ($image) {
                        return [
                            'id' => $image->id,
                            'image_path' => asset('projects/images/' . $image->image_path),
                            'title' => [
                                'ar' => $image->title_ar,
                                'en' => $image->title_en
                            ],
                            'description' => [
                                'ar' => $image->description_ar,
                                'en' => $image->description_en
                            ],
                            'order' => $image->order,
                            'is_featured' => $image->is_featured
                        ];
                    }),
                    'exterior' => $images->get('exterior', collect())->map(function ($image) {
                        return [
                            'id' => $image->id,
                            'image_path' => asset('projects/images/' . $image->image_path),
                            'title' => [
                                'ar' => $image->title_ar,
                                'en' => $image->title_en
                            ],
                            'description' => [
                                'ar' => $image->description_ar,
                                'en' => $image->description_en
                            ],
                            'order' => $image->order,
                            'is_featured' => $image->is_featured
                        ];
                    }),
                    'floorplan' => $images->get('floorplan', collect())->map(function ($image) {
                        return [
                            'id' => $image->id,
                            'image_path' => asset('projects/images/' . $image->image_path),
                            'title' => [
                                'ar' => $image->title_ar,
                                'en' => $image->title_en
                            ],
                            'description' => [
                                'ar' => $image->description_ar,
                                'en' => $image->description_en
                            ],
                            'order' => $image->order,
                            'is_featured' => $image->is_featured
                        ];
                    }),
                    'featured' => $images->get('featured', collect())->map(function ($image) {
                        return [
                            'id' => $image->id,
                            'image_path' => asset('projects/images/' . $image->image_path),
                            'title' => [
                                'ar' => $image->title_ar,
                                'en' => $image->title_en
                            ],
                            'description' => [
                                'ar' => $image->description_ar,
                                'en' => $image->description_en
                            ],
                            'order' => $image->order,
                            'is_featured' => $image->is_featured
                        ];
                    })
                ];
            }),
            'descriptions' => $this->whenLoaded('descriptions', function () {
                return $this->descriptions->map(function ($description) {
                    return [
                        'id' => $description->id,
                        'section_type' => $description->section_type,
                        'title' => [
                            'ar' => $description->title_ar,
                            'en' => $description->title_en
                        ],
                        'content' => [
                            'ar' => $description->content_ar,
                            'en' => $description->content_en
                        ],
                        'order_index' => $description->order_index,
                        'is_active' => $description->is_active
                    ];
                });
            }),
            'content_blocks' => $this->whenLoaded('contentBlocks', function () {
                return $this->contentBlocks->map(function ($block) {
                    return [
                        'id' => $block->id,
                        'title' => [
                            'ar' => $block->title_ar,
                            'en' => $block->title_en
                        ],
                        'content' => [
                            'ar' => $block->content_ar,
                            'en' => $block->content_en
                        ],
                        'order' => $block->order,
                        'is_active' => $block->is_active,
                        'images' => $block->whenLoaded('images', function () use ($block) {
                            return $block->images->map(function ($image) {
                                return [
                                    'id' => $image->id,
                                    'image_path' => asset('projects/content-blocks/' . $image->image_path),
                                    'title' => [
                                        'ar' => $image->title_ar,
                                        'en' => $image->title_en
                                    ],
                                    'description' => [
                                        'ar' => $image->description_ar,
                                        'en' => $image->description_en
                                    ],
                                    'order' => $image->order,
                                    'is_active' => $image->is_active
                                ];
                            });
                        })
                    ];
                });
            }),
            'amenities' => $this->whenLoaded('amenities', function () {
                return $this->amenities->map(function ($amenity) {
                    return [
                        'id' => $amenity->id,
                        'type' => $amenity->amenity_type,
                        'name' => [
                            'ar' => $amenity->getAmenityName('ar'),
                            'en' => $amenity->getAmenityName('en')
                        ],
                        'icon' => $amenity->getAmenityIconAttribute(),
                        'is_active' => $amenity->is_active
                    ];
                });
            }),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
