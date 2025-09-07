<?php

namespace App\Http\Controllers\Apis;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectAmenity;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ProjectAmenityController extends Controller
{
    /**
     * Display a listing of project amenities.
     */
    public function index(string $projectId): JsonResponse
    {
        try {
            $project = Project::findOrFail($projectId);
            $amenities = $project->amenities()->orderBy('amenity_type')->get();
            
            // Get available amenity types
            $availableTypes = ProjectAmenity::getAmenityTypesData();
            
            // Count active and inactive amenities
            $activeCount = $project->amenities()->where('is_active', true)->count();
            $inactiveCount = $project->amenities()->where('is_active', false)->count();
            $totalCount = $project->amenities()->count();
            
            return response()->json([
                'status' => 'success',
                'message' => 'Project amenities retrieved successfully',
                'data' => $amenities,
                'project' => [
                    'id' => $project->id,
                    'name' => $project->name
                ],
                'statistics' => [
                    'active_count' => $activeCount,
                    'inactive_count' => $inactiveCount,
                    'total_count' => $totalCount,
                    'available_count' => count($availableTypes) - $totalCount
                ],
                'available_types' => $availableTypes
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve project amenities',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created project amenity.
     */
    public function store(Request $request, string $projectId): JsonResponse
    {
        try {
            $project = Project::findOrFail($projectId);
            
            $request->validate([
                'amenity_type' => 'required|string|in:' . implode(',', ProjectAmenity::getAvailableTypes()),
                'is_active' => 'nullable|boolean',
            ]);

            // Check if amenity already exists for this project
            $existingAmenity = $project->amenities()->where('amenity_type', $request->amenity_type)->first();
            if ($existingAmenity) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Amenity already exists for this project',
                    'data' => $existingAmenity
                ], 400);
            }

            $amenity = $project->amenities()->create([
                'amenity_type' => $request->amenity_type,
                'is_active' => $request->is_active ?? true,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Project amenity created successfully',
                'data' => $amenity
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create project amenity',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified project amenity.
     */
    public function show(string $projectId, string $amenityId): JsonResponse
    {
        try {
            $project = Project::findOrFail($projectId);
            $amenity = $project->amenities()->findOrFail($amenityId);
            
            return response()->json([
                'status' => 'success',
                'message' => 'Project amenity retrieved successfully',
                'data' => $amenity
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Project amenity not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Update the specified project amenity.
     */
    public function update(Request $request, string $projectId, string $amenityId): JsonResponse
    {
        try {
            $project = Project::findOrFail($projectId);
            $amenity = $project->amenities()->findOrFail($amenityId);
            
            $request->validate([
                'amenity_type' => 'sometimes|required|string|in:' . implode(',', ProjectAmenity::getAvailableTypes()),
                'is_active' => 'nullable|boolean',
            ]);

            $amenity->update($request->all());

            return response()->json([
                'status' => 'success',
                'message' => 'Project amenity updated successfully',
                'data' => $amenity->fresh()
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update project amenity',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified project amenity.
     */
    public function destroy(string $projectId, string $amenityId): JsonResponse
    {
        try {
            $project = Project::findOrFail($projectId);
            $amenity = $project->amenities()->findOrFail($amenityId);
            
            $amenity->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Project amenity deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete project amenity',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle amenity status.
     */
    public function toggle(string $projectId, string $amenityId): JsonResponse
    {
        try {
            $project = Project::findOrFail($projectId);
            $amenity = $project->amenities()->findOrFail($amenityId);
            
            $amenity->update(['is_active' => !$amenity->is_active]);

            return response()->json([
                'status' => 'success',
                'message' => 'Project amenity status toggled successfully',
                'data' => $amenity->fresh()
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to toggle project amenity status',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk update project amenities.
     */
    public function bulkUpdate(Request $request, string $projectId): JsonResponse
    {
        try {
            $project = Project::findOrFail($projectId);
            
            $request->validate([
                'amenities' => 'required|array',
                'amenities.*.amenity_type' => 'required|string|in:' . implode(',', ProjectAmenity::getAvailableTypes()),
                'amenities.*.is_active' => 'required|boolean',
            ]);

            // Clear existing amenities
            $project->amenities()->delete();

            // Create new amenities
            $amenities = [];
            foreach ($request->amenities as $amenityData) {
                $amenity = $project->amenities()->create([
                    'amenity_type' => $amenityData['amenity_type'],
                    'is_active' => $amenityData['is_active'],
                ]);
                $amenities[] = $amenity;
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Project amenities updated successfully',
                'data' => $amenities,
                'count' => count($amenities)
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update project amenities',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get active amenities only.
     */
    public function getActive(string $projectId): JsonResponse
    {
        try {
            $project = Project::findOrFail($projectId);
            $amenities = $project->amenities()->where('is_active', true)->orderBy('amenity_type')->get();
            
            return response()->json([
                'status' => 'success',
                'message' => 'Project active amenities retrieved successfully',
                'data' => $amenities,
                'count' => $amenities->count()
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve active amenities',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get available amenity types for project.
     */
    public function getAvailableTypes(string $projectId): JsonResponse
    {
        try {
            $project = Project::findOrFail($projectId);
            
            // Get all available types
            $allTypes = ProjectAmenity::getAmenityTypesData();
            
            // Get existing amenity types for this project
            $existingTypes = $project->amenities()->pluck('amenity_type')->toArray();
            
            // Filter out existing types
            $availableTypes = array_diff_key($allTypes, array_flip($existingTypes));

            return response()->json([
                'status' => 'success',
                'message' => 'Available amenity types retrieved successfully',
                'data' => $availableTypes,
                'existing_types' => $existingTypes,
                'count' => count($availableTypes)
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve available amenity types',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
