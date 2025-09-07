<?php

namespace App\Http\Controllers\Apis;

use App\Http\Controllers\Controller;
use App\Models\AboutUsSection;
use App\Models\AboutUsImage;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AboutUsController extends Controller
{
    /**
     * Display a listing of about us sections.
     */
    public function index(): JsonResponse
    {
        try {
            $sections = AboutUsSection::with(['images' => function($query) {
                $query->orderBy('order_index', 'asc');
            }])->orderBy('order_index', 'asc')->get();
            
            // Transform the data to include image URLs
            $sections->each(function ($section) {
                if ($section->main_image) {
                    $section->main_image = asset('uploads/aboutus/' . $section->main_image);
                }
                $section->images->each(function ($image) {
                    $image->image_path = $image->getImageUrlAttribute();
                });
            });
            
            return response()->json([
                'status' => 'success',
                'message' => 'About us sections retrieved successfully',
                'data' => $sections
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve about us sections',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created about us section.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'section_name' => 'required|string|max:255|unique:about_us_sections',
                'title_ar' => 'required|string|max:255',
                'title_en' => 'required|string|max:255',
                'content_ar' => 'required|string',
                'content_en' => 'required|string',
                'main_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
                'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
                'order_index' => 'nullable|integer|min:0',
                'is_active' => 'nullable|boolean'
            ]);

            // Handle main image upload
            $mainImageName = null;
            if ($request->hasFile('main_image')) {
                $image = $request->file('main_image');
                $mainImageName = time() . '_main_' . $request->section_name . '.' . $image->getClientOriginalExtension();
                
                $uploadPath = public_path('uploads/aboutus');
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }
                
                $image->move($uploadPath, $mainImageName);
            }

            // Create section
            $section = AboutUsSection::create([
                'section_name' => $request->section_name,
                'title_ar' => $request->title_ar,
                'title_en' => $request->title_en,
                'content_ar' => $request->content_ar,
                'content_en' => $request->content_en,
                'main_image' => $mainImageName,
                'order_index' => $request->order_index ?: 0,
                'is_active' => $request->has('is_active')
            ]);

            // Handle additional images
            if ($request->hasFile('images')) {
                $uploadPath = public_path('uploads/aboutus/images');
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }

                foreach ($request->file('images') as $index => $image) {
                    if ($image->isValid()) {
                        $imageName = time() . '_' . $section->id . '_' . $index . '.' . $image->getClientOriginalExtension();
                        $image->move($uploadPath, $imageName);

                        AboutUsImage::create([
                            'about_us_section_id' => $section->id,
                            'image_path' => $imageName,
                            'alt_text_ar' => $image->getClientOriginalName(),
                            'alt_text_en' => $image->getClientOriginalName(),
                            'caption_ar' => null,
                            'caption_en' => null,
                            'order_index' => $index,
                            'is_active' => true
                        ]);
                    }
                }
            }

            $section->load('images');

            // Transform the data to include image URLs
            if ($section->main_image) {
                $section->main_image = asset('uploads/aboutus/' . $section->main_image);
            }
            $section->images->each(function ($image) {
                $image->image_path = $image->getImageUrlAttribute();
            });

            return response()->json([
                'status' => 'success',
                'message' => 'About us section created successfully',
                'data' => $section
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create about us section',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified about us section.
     */
    public function show(string $id): JsonResponse
    {
        try {
            $section = AboutUsSection::with(['images' => function($query) {
                $query->orderBy('order_index', 'asc');
            }])->findOrFail($id);
            
            // Transform the data to include image URLs
            if ($section->main_image) {
                $section->main_image = asset('uploads/aboutus/' . $section->main_image);
            }
            $section->images->each(function ($image) {
                $image->image_path = $image->getImageUrlAttribute();
            });
            
            return response()->json([
                'status' => 'success',
                'message' => 'About us section retrieved successfully',
                'data' => $section
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'About us section not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Update the specified about us section.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        try {
            $section = AboutUsSection::findOrFail($id);
            
            $request->validate([
                'section_name' => 'sometimes|required|string|max:255|unique:about_us_sections,section_name,' . $id,
                'title_ar' => 'sometimes|required|string|max:255',
                'title_en' => 'sometimes|required|string|max:255',
                'content_ar' => 'sometimes|required|string',
                'content_en' => 'sometimes|required|string',
                'main_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
                'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
                'order_index' => 'nullable|integer|min:0',
                'is_active' => 'nullable|boolean'
            ]);

            // Handle main image upload
            if ($request->hasFile('main_image')) {
                // Delete old main image if exists
                if ($section->main_image && file_exists(public_path('uploads/aboutus/' . $section->main_image))) {
                    unlink(public_path('uploads/aboutus/' . $section->main_image));
                }

                $image = $request->file('main_image');
                $mainImageName = time() . '_main_' . $request->section_name . '.' . $image->getClientOriginalExtension();
                
                $uploadPath = public_path('uploads/aboutus');
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }
                
                $image->move($uploadPath, $mainImageName);
                $section->main_image = $mainImageName;
            }

            // Update section
            $section->update([
                'section_name' => $request->section_name ?: $section->section_name,
                'title_ar' => $request->title_ar ?: $section->title_ar,
                'title_en' => $request->title_en ?: $section->title_en,
                'content_ar' => $request->content_ar ?: $section->content_ar,
                'content_en' => $request->content_en ?: $section->content_en,
                'order_index' => $request->order_index !== null ? $request->order_index : $section->order_index,
                'is_active' => $request->has('is_active') ? $request->boolean('is_active') : $section->is_active
            ]);

            // Handle additional images
            if ($request->hasFile('images')) {
                $uploadPath = public_path('uploads/aboutus/images');
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }

                $maxOrder = $section->images()->max('order_index') ?: -1;

                foreach ($request->file('images') as $index => $image) {
                    if ($image->isValid()) {
                        $imageName = time() . '_' . $section->id . '_' . ($maxOrder + $index + 1) . '.' . $image->getClientOriginalExtension();
                        $image->move($uploadPath, $imageName);

                        AboutUsImage::create([
                            'about_us_section_id' => $section->id,
                            'image_path' => $imageName,
                            'alt_text_ar' => $image->getClientOriginalName(),
                            'alt_text_en' => $image->getClientOriginalName(),
                            'caption_ar' => null,
                            'caption_en' => null,
                            'order_index' => $maxOrder + $index + 1,
                            'is_active' => true
                        ]);
                    }
                }
            }

            $section->load('images');

            // Transform the data to include image URLs
            if ($section->main_image) {
                $section->main_image = asset('uploads/aboutus/' . $section->main_image);
            }
            $section->images->each(function ($image) {
                $image->image_path = $image->getImageUrlAttribute();
            });

            return response()->json([
                'status' => 'success',
                'message' => 'About us section updated successfully',
                'data' => $section
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update about us section',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified about us section.
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $section = AboutUsSection::findOrFail($id);
            
            // Delete main image if exists
            if ($section->main_image && file_exists(public_path('uploads/aboutus/' . $section->main_image))) {
                unlink(public_path('uploads/aboutus/' . $section->main_image));
            }

            // Delete additional images
            foreach ($section->images as $image) {
                if (file_exists(public_path('uploads/aboutus/images/' . $image->image_path))) {
                    unlink(public_path('uploads/aboutus/images/' . $image->image_path));
                }
            }

            $section->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'About us section deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete about us section',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a specific image from a section.
     */
    public function deleteImage(string $sectionId, string $imageId): JsonResponse
    {
        try {
            $section = AboutUsSection::findOrFail($sectionId);
            $image = AboutUsImage::where('about_us_section_id', $sectionId)
                                ->where('id', $imageId)
                                ->firstOrFail();

            // Delete image file
            if (file_exists(public_path('uploads/aboutus/images/' . $image->image_path))) {
                unlink(public_path('uploads/aboutus/images/' . $image->image_path));
            }

            $image->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Image deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete image',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
