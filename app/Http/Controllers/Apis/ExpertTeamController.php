<?php

namespace App\Http\Controllers\Apis;

use App\Http\Controllers\Controller;
use App\Models\ExpertTeam;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ExpertTeamController extends Controller
{
    /**
     * Display a listing of expert team members.
     */
    public function index(): JsonResponse
    {
        try {
            $expertTeam = ExpertTeam::orderBy('order_index', 'asc')->get();
            
            // Transform image to full URL
            $expertTeam->each(function ($member) {
                if ($member->image) {
                    $member->image = asset('uploads/expert-team/' . $member->image);
                }
            });
            
            return response()->json([
                'status' => 'success',
                'message' => 'Expert team members retrieved successfully',
                'data' => $expertTeam
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve expert team members',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created expert team member.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'name_ar' => 'required|string|max:255',
                'name_en' => 'required|string|max:255',
                'position_ar' => 'required|string|max:255',
                'position_en' => 'required|string|max:255',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
                'order_index' => 'nullable|integer|min:0'
            ]);

            // Handle image upload
            $imageName = null;
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '_expert_team.' . $image->getClientOriginalExtension();
                
                $uploadPath = public_path('uploads/expert-team');
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }
                
                $image->move($uploadPath, $imageName);
            }

            // Create expert team member
            $expertTeamMember = ExpertTeam::create([
                'name_ar' => $request->name_ar,
                'name_en' => $request->name_en,
                'position_ar' => $request->position_ar,
                'position_en' => $request->position_en,
                'image' => $imageName,
                'order_index' => $request->order_index ?: 0
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Expert team member created successfully',
                'data' => $expertTeamMember
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create expert team member',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified expert team member.
     */
    public function show(string $id): JsonResponse
    {
        try {
            $expertTeamMember = ExpertTeam::findOrFail($id);
            
            return response()->json([
                'status' => 'success',
                'message' => 'Expert team member retrieved successfully',
                'data' => $expertTeamMember
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Expert team member not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Update the specified expert team member.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        try {
            $expertTeamMember = ExpertTeam::findOrFail($id);
            
            $request->validate([
                'name_ar' => 'sometimes|required|string|max:255',
                'name_en' => 'sometimes|required|string|max:255',
                'position_ar' => 'sometimes|required|string|max:255',
                'position_en' => 'sometimes|required|string|max:255',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
                'order_index' => 'nullable|integer|min:0'
            ]);

            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($expertTeamMember->image && file_exists(public_path('uploads/expert-team/' . $expertTeamMember->image))) {
                    unlink(public_path('uploads/expert-team/' . $expertTeamMember->image));
                }

                $image = $request->file('image');
                $imageName = time() . '_expert_team.' . $image->getClientOriginalExtension();
                
                $uploadPath = public_path('uploads/expert-team');
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }
                
                $image->move($uploadPath, $imageName);
                $expertTeamMember->image = $imageName;
            }

            // Update expert team member
            $expertTeamMember->update([
                'name_ar' => $request->name_ar ?: $expertTeamMember->name_ar,
                'name_en' => $request->name_en ?: $expertTeamMember->name_en,
                'position_ar' => $request->position_ar ?: $expertTeamMember->position_ar,
                'position_en' => $request->position_en ?: $expertTeamMember->position_en,
                'order_index' => $request->order_index !== null ? $request->order_index : $expertTeamMember->order_index
            ]);

            // Transform image to full URL
            if ($expertTeamMember->image) {
                $expertTeamMember->image = asset('uploads/expert-team/' . $expertTeamMember->image);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Expert team member updated successfully',
                'data' => $expertTeamMember
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update expert team member',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified expert team member.
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $expertTeamMember = ExpertTeam::findOrFail($id);
            
            // Delete image if exists
            if ($expertTeamMember->image && file_exists(public_path('uploads/expert-team/' . $expertTeamMember->image))) {
                unlink(public_path('uploads/expert-team/' . $expertTeamMember->image));
            }

            $expertTeamMember->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Expert team member deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete expert team member',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
