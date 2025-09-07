<?php

namespace App\Http\Controllers\Apis;

use App\Http\Controllers\Controller;
use App\Models\Achievement;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AchievementController extends Controller
{
    /**
     * Display a listing of achievements.
     */
    public function index(): JsonResponse
    {
        try {
            $achievements = Achievement::orderBy('order_index', 'asc')->get();
            
            return response()->json([
                'status' => 'success',
                'message' => 'Achievements retrieved successfully',
                'data' => $achievements
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve achievements',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created achievement.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'name_ar' => 'required|string|max:255',
                'name_en' => 'required|string|max:255',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
                'order_index' => 'nullable|integer|min:0'
            ]);

            // Handle image upload
            $imageName = null;
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '_achievement.' . $image->getClientOriginalExtension();
                
                $uploadPath = public_path('uploads/achievements');
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }
                
                $image->move($uploadPath, $imageName);
            }

            // Create achievement
            $achievement = Achievement::create([
                'name_ar' => $request->name_ar,
                'name_en' => $request->name_en,
                'image' => $imageName,
                'order_index' => $request->order_index ?: 0
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Achievement created successfully',
                'data' => $achievement
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create achievement',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified achievement.
     */
    public function show(string $id): JsonResponse
    {
        try {
            $achievement = Achievement::findOrFail($id);
            
            return response()->json([
                'status' => 'success',
                'message' => 'Achievement retrieved successfully',
                'data' => $achievement
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Achievement not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Update the specified achievement.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        try {
            $achievement = Achievement::findOrFail($id);
            
            $request->validate([
                'name_ar' => 'sometimes|required|string|max:255',
                'name_en' => 'sometimes|required|string|max:255',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
                'order_index' => 'nullable|integer|min:0'
            ]);

            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($achievement->image && file_exists(public_path('uploads/achievements/' . $achievement->image))) {
                    unlink(public_path('uploads/achievements/' . $achievement->image));
                }

                $image = $request->file('image');
                $imageName = time() . '_achievement.' . $image->getClientOriginalExtension();
                
                $uploadPath = public_path('uploads/achievements');
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }
                
                $image->move($uploadPath, $imageName);
                $achievement->image = $imageName;
            }

            // Update achievement
            $achievement->update([
                'name_ar' => $request->name_ar ?: $achievement->name_ar,
                'name_en' => $request->name_en ?: $achievement->name_en,
                'order_index' => $request->order_index !== null ? $request->order_index : $achievement->order_index
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Achievement updated successfully',
                'data' => $achievement
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update achievement',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified achievement.
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $achievement = Achievement::findOrFail($id);
            
            // Delete image if exists
            if ($achievement->image && file_exists(public_path('uploads/achievements/' . $achievement->image))) {
                unlink(public_path('uploads/achievements/' . $achievement->image));
            }

            $achievement->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Achievement deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete achievement',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
