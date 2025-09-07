<?php

namespace App\Http\Controllers\Apis;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class SliderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        try {
            $sliders = Slider::orderBy('order')->get();
            
            // Transform background_image to full URL
            $sliders->each(function ($slider) {
                if ($slider->background_image) {
                    $slider->background_image = asset('sliders/' . $slider->background_image);
                }
            });
            
            return response()->json([
                'status' => 'success',
                'message' => 'Sliders retrieved successfully',
                'data' => $sliders
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve sliders',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'title_ar' => 'required|string|max:255',
                'title_en' => 'required|string|max:255',
                'subtitle_ar' => 'nullable|string|max:255',
                'subtitle_en' => 'nullable|string|max:255',
                'button1_text_ar' => 'nullable|string|max:255',
                'button1_text_en' => 'nullable|string|max:255',
                'button1_link' => 'nullable|url|max:255',
                'button2_text_ar' => 'nullable|string|max:255',
                'button2_text_en' => 'nullable|string|max:255',
                'button2_link' => 'nullable|url|max:255',
                'features_ar' => 'nullable|string',
                'features_en' => 'nullable|string',
                'brochure_link' => 'nullable|url|max:255',
                'order' => 'nullable|integer|min:0',
                'background_image' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            ]);

            $slider = Slider::create($request->all());

            if ($request->hasFile('background_image')) {
                $imageName = time() . '_slider.' . $request->background_image->extension();
                $request->background_image->move(public_path('sliders'), $imageName);
                $slider->background_image = $imageName;
                $slider->save();
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Slider created successfully',
                'data' => $slider
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create slider',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        try {
            $slider = Slider::findOrFail($id);
            
            return response()->json([
                'status' => 'success',
                'message' => 'Slider retrieved successfully',
                'data' => $slider
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Slider not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        try {
            $slider = Slider::findOrFail($id);
            
            $request->validate([
                'title_ar' => 'sometimes|required|string|max:255',
                'title_en' => 'sometimes|required|string|max:255',
                'subtitle_ar' => 'nullable|string|max:255',
                'subtitle_en' => 'nullable|string|max:255',
                'button1_text_ar' => 'nullable|string|max:255',
                'button1_text_en' => 'nullable|string|max:255',
                'button1_link' => 'nullable|url|max:255',
                'button2_text_ar' => 'nullable|string|max:255',
                'button2_text_en' => 'nullable|string|max:255',
                'button2_link' => 'nullable|url|max:255',
                'features_ar' => 'nullable|string',
                'features_en' => 'nullable|string',
                'brochure_link' => 'nullable|url|max:255',
                'order' => 'nullable|integer|min:0',
                'background_image' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            ]);

            $slider->update($request->except('background_image'));

            if ($request->hasFile('background_image')) {
                // Delete old image if exists
                if ($slider->background_image && file_exists(public_path('sliders/' . $slider->background_image))) {
                    unlink(public_path('sliders/' . $slider->background_image));
                }
                
                $imageName = time() . '_slider.' . $request->background_image->extension();
                $request->background_image->move(public_path('sliders'), $imageName);
                $slider->background_image = $imageName;
                $slider->save();
            }

            // Transform background_image to full URL
            if ($slider->background_image) {
                $slider->background_image = asset('sliders/' . $slider->background_image);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Slider updated successfully',
                'data' => $slider
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update slider',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $slider = Slider::findOrFail($id);
            
            // Delete background image if exists
            if ($slider->background_image && file_exists(public_path('sliders/' . $slider->background_image))) {
                unlink(public_path('sliders/' . $slider->background_image));
            }
            
            $slider->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Slider deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete slider',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
