<?php

namespace App\Http\Controllers\Apis;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectDetail;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ProjectDetailController extends Controller
{
    /**
     * Display a listing of project details.
     */
    public function index(string $projectId): JsonResponse
    {
        try {
            $project = Project::findOrFail($projectId);
            $details = $project->projectDetails()->orderBy('order')->get();
            
            return response()->json([
                'status' => 'success',
                'message' => 'Project details retrieved successfully',
                'data' => $details,
                'project' => [
                    'id' => $project->id,
                    'name' => $project->name
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve project details',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created project detail.
     */
    public function store(Request $request, string $projectId): JsonResponse
    {
        try {
            $project = Project::findOrFail($projectId);
            
            $request->validate([
                'detail_ar' => 'required|string|max:255',
                'detail_en' => 'required|string|max:255',
                'detail_value_ar' => 'required|string',
                'detail_value_en' => 'required|string',
                'order' => 'nullable|integer|min:0',
            ]);

            $detail = $project->projectDetails()->create([
                'detail_ar' => $request->detail_ar,
                'detail_en' => $request->detail_en,
                'detail_value_ar' => $request->detail_value_ar,
                'detail_value_en' => $request->detail_value_en,
                'order' => $request->order ?? 0,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Project detail created successfully',
                'data' => $detail
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create project detail',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified project detail.
     */
    public function show(string $projectId, string $detailId): JsonResponse
    {
        try {
            $project = Project::findOrFail($projectId);
            $detail = $project->projectDetails()->findOrFail($detailId);
            
            return response()->json([
                'status' => 'success',
                'message' => 'Project detail retrieved successfully',
                'data' => $detail
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Project detail not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Update the specified project detail.
     */
    public function update(Request $request, string $projectId, string $detailId): JsonResponse
    {
        try {
            $project = Project::findOrFail($projectId);
            $detail = $project->projectDetails()->findOrFail($detailId);
            
            $request->validate([
                'detail_ar' => 'sometimes|required|string|max:255',
                'detail_en' => 'sometimes|required|string|max:255',
                'detail_value_ar' => 'sometimes|required|string',
                'detail_value_en' => 'sometimes|required|string',
                'order' => 'nullable|integer|min:0',
            ]);

            $detail->update($request->all());

            return response()->json([
                'status' => 'success',
                'message' => 'Project detail updated successfully',
                'data' => $detail->fresh()
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update project detail',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified project detail.
     */
    public function destroy(string $projectId, string $detailId): JsonResponse
    {
        try {
            $project = Project::findOrFail($projectId);
            $detail = $project->projectDetails()->findOrFail($detailId);
            
            $detail->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Project detail deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete project detail',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk update project details order.
     */
    public function bulkUpdateOrder(Request $request, string $projectId): JsonResponse
    {
        try {
            $project = Project::findOrFail($projectId);
            
            $request->validate([
                'details' => 'required|array',
                'details.*.id' => 'required|exists:project_details,id',
                'details.*.order' => 'required|integer|min:0',
            ]);

            foreach ($request->details as $detailData) {
                $detail = $project->projectDetails()->find($detailData['id']);
                if ($detail) {
                    $detail->update(['order' => $detailData['order']]);
                }
            }

            $updatedDetails = $project->projectDetails()->orderBy('order')->get();

            return response()->json([
                'status' => 'success',
                'message' => 'Project details order updated successfully',
                'data' => $updatedDetails
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update project details order',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
