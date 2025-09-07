<?php

namespace App\Http\Controllers\Apis;

use App\Http\Controllers\Controller;
use App\Models\Award;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AwardController extends Controller
{
    /**
     * Display a listing of awards.
     */
    public function index(): JsonResponse
    {
        try {
            $awards = Award::orderBy('order_index', 'asc')->get();
            
            // Transform image to full URL
            $awards->each(function ($award) {
                if ($award->image) {
                    $award->image = asset('uploads/awards/' . $award->image);
                }
            });
            
            return response()->json([
                'status' => 'success',
                'message' => 'Awards retrieved successfully',
                'data' => $awards
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve awards',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created award.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'title_ar' => 'required|string|max:255',
                'title_en' => 'required|string|max:255',
                'description_ar' => 'nullable|string',
                'description_en' => 'nullable|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
                'year' => 'nullable|string|max:4',
                'category' => 'nullable|string|max:255',
                'order_index' => 'nullable|integer|min:0',
                'is_active' => 'nullable|boolean'
            ]);

            // Handle image upload
            $imageName = null;
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '_award.' . $image->getClientOriginalExtension();
                
                $uploadPath = public_path('uploads/awards');
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }
                
                $image->move($uploadPath, $imageName);
            }

            // Create award
            $award = Award::create([
                'title_ar' => $request->title_ar,
                'title_en' => $request->title_en,
                'description_ar' => $request->description_ar,
                'description_en' => $request->description_en,
                'image' => $imageName,
                'year' => $request->year,
                'category' => $request->category,
                'order_index' => $request->order_index ?: 0,
                'is_active' => $request->has('is_active')
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Award created successfully',
                'data' => $award
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create award',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified award.
     */
    public function show(string $id): JsonResponse
    {
        try {
            $award = Award::findOrFail($id);
            
            return response()->json([
                'status' => 'success',
                'message' => 'Award retrieved successfully',
                'data' => $award
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Award not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Update the specified award.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        try {
            $award = Award::findOrFail($id);
            
            $request->validate([
                'title_ar' => 'sometimes|required|string|max:255',
                'title_en' => 'sometimes|required|string|max:255',
                'description_ar' => 'nullable|string',
                'description_en' => 'nullable|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
                'year' => 'nullable|string|max:4',
                'category' => 'nullable|string|max:255',
                'order_index' => 'nullable|integer|min:0',
                'is_active' => 'nullable|boolean'
            ]);

            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($award->image && file_exists(public_path('uploads/awards/' . $award->image))) {
                    unlink(public_path('uploads/awards/' . $award->image));
                }

                $image = $request->file('image');
                $imageName = time() . '_award.' . $image->getClientOriginalExtension();
                
                $uploadPath = public_path('uploads/awards');
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }
                
                $image->move($uploadPath, $imageName);
                $award->image = $imageName;
            }

            // Update award
            $award->update([
                'title_ar' => $request->title_ar ?: $award->title_ar,
                'title_en' => $request->title_en ?: $award->title_en,
                'description_ar' => $request->description_ar,
                'description_en' => $request->description_en,
                'year' => $request->year,
                'category' => $request->category,
                'order_index' => $request->order_index !== null ? $request->order_index : $award->order_index,
                'is_active' => $request->has('is_active') ? $request->boolean('is_active') : $award->is_active
            ]);

            // Transform image to full URL
            if ($award->image) {
                $award->image = asset('uploads/awards/' . $award->image);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Award updated successfully',
                'data' => $award
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update award',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified award.
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $award = Award::findOrFail($id);
            
            // Delete image if exists
            if ($award->image && file_exists(public_path('uploads/awards/' . $award->image))) {
                unlink(public_path('uploads/awards/' . $award->image));
            }

            $award->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Award deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete award',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
