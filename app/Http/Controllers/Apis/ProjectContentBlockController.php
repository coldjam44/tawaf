<?php

namespace App\Http\Controllers\Apis;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectContentBlock;
use App\Models\ProjectContentBlockImage;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ProjectContentBlockController extends Controller
{
    /**
     * Display a listing of project content blocks.
     */
    public function index(string $projectId): JsonResponse
    {
        try {
            $project = Project::findOrFail($projectId);
            $contentBlocks = $project->contentBlocks()->with('images')->where('is_active', true)->orderBy('order')->get();
            
            return response()->json([
                'status' => 'success',
                'message' => 'Project content blocks retrieved successfully',
                'data' => $contentBlocks,
                'project' => [
                    'id' => $project->id,
                    'name' => $project->name
                ],
                'count' => $contentBlocks->count()
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve project content blocks',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created project content block.
     */
    public function store(Request $request, string $projectId): JsonResponse
    {
        try {
            $project = Project::findOrFail($projectId);
            
            $request->validate([
                'title_ar' => 'required|string|max:255',
                'title_en' => 'required|string|max:255',
                'content_ar' => 'required|string',
                'content_en' => 'required|string',
                'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif',
                'image_title_ar' => 'nullable|string|max:255',
                'image_title_en' => 'nullable|string|max:255',
                'image_description_ar' => 'nullable|string',
                'image_description_en' => 'nullable|string',
                'order' => 'nullable|integer|min:0',
                'is_active' => 'nullable|boolean'
            ]);

            // Get the next order number if not provided
            $order = $request->order ?? ($project->contentBlocks()->max('order') + 1);
            
            $contentBlock = $project->contentBlocks()->create([
                'title_ar' => $request->title_ar,
                'title_en' => $request->title_en,
                'content_ar' => $request->content_ar,
                'content_en' => $request->content_en,
                'order' => $order,
                'is_active' => $request->is_active ?? true
            ]);

            // Handle image uploads if any
            $uploadedImages = [];
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $imageName = time() . '_' . uniqid() . '.' . $image->extension();
                    $image->move(public_path('projects/content-blocks'), $imageName);

                    $projectImage = ProjectContentBlockImage::create([
                        'content_block_id' => $contentBlock->id,
                        'image_path' => $imageName,
                        'title_ar' => $request->image_title_ar ?? 'صورة ' . ($contentBlock->images()->count() + 1),
                        'title_en' => $request->image_title_en ?? 'Image ' . ($contentBlock->images()->count() + 1),
                        'description_ar' => $request->image_description_ar,
                        'description_en' => $request->image_description_en,
                        'order' => $contentBlock->images()->count(),
                        'is_active' => true
                    ]);

                    $uploadedImages[] = $projectImage;
                }
            }
            
            $contentBlock->load('images');

            // Transform image_path to full URL
            $contentBlock->images->each(function ($image) {
                $image->image_path = asset('projects/content-blocks/' . $image->image_path);
            });

            return response()->json([
                'status' => 'success',
                'message' => 'Project content block created successfully',
                'data' => $contentBlock
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create project content block',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified project content block.
     */
    public function show(string $projectId, string $blockId): JsonResponse
    {
        try {
            $project = Project::findOrFail($projectId);
            $contentBlock = $project->contentBlocks()->findOrFail($blockId);
            
            // Load images relationship
            $contentBlock->load('images');
            
            return response()->json([
                'status' => 'success',
                'message' => 'Project content block retrieved successfully',
                'data' => $contentBlock
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Project content block not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Update the specified project content block.
     */
    public function update(Request $request, string $projectId, string $blockId): JsonResponse
    {
        try {
            $project = Project::findOrFail($projectId);
            $contentBlock = $project->contentBlocks()->findOrFail($blockId);
            
            $request->validate([
                'title_ar' => 'sometimes|required|string|max:255',
                'title_en' => 'sometimes|required|string|max:255',
                'content_ar' => 'sometimes|required|string',
                'content_en' => 'sometimes|required|string',
                'order' => 'nullable|integer|min:0',
                'is_active' => 'nullable|boolean'
            ]);

            $updateData = $request->only(['title_ar', 'title_en', 'content_ar', 'content_en', 'order', 'is_active']);
            
            $contentBlock->update($updateData);
            
            // Load images relationship
            $contentBlock->load('images');
            
            return response()->json([
                'status' => 'success',
                'message' => 'Project content block updated successfully',
                'data' => $contentBlock->fresh()->load('images')
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update project content block',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified project content block.
     */
    public function destroy(string $projectId, string $blockId): JsonResponse
    {
        try {
            $project = Project::findOrFail($projectId);
            $contentBlock = $project->contentBlocks()->findOrFail($blockId);
            
            // Delete associated images
            foreach ($contentBlock->images as $image) {
                if (file_exists(public_path('projects/content-blocks/' . $image->image_path))) {
                    unlink(public_path('projects/content-blocks/' . $image->image_path));
                }
                $image->delete();
            }
            
            $contentBlock->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Project content block deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete project content block',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle active status of content block.
     */
    public function toggle(string $projectId, string $blockId): JsonResponse
    {
        try {
            $project = Project::findOrFail($projectId);
            $contentBlock = $project->contentBlocks()->findOrFail($blockId);
            
            $contentBlock->update(['is_active' => !$contentBlock->is_active]);
            
            return response()->json([
                'status' => 'success',
                'message' => 'Content block status toggled successfully',
                'data' => [
                    'id' => $contentBlock->id,
                    'is_active' => $contentBlock->is_active
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to toggle content block status',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update order of content blocks.
     */
    public function updateOrder(Request $request, string $projectId): JsonResponse
    {
        try {
            $project = Project::findOrFail($projectId);
            
            $request->validate([
                'blocks' => 'required|array',
                'blocks.*.id' => 'required|exists:project_content_blocks,id',
                'blocks.*.order' => 'required|integer|min:0'
            ]);
            
            foreach ($request->blocks as $block) {
                $contentBlock = $project->contentBlocks()->find($block['id']);
                if ($contentBlock) {
                    $contentBlock->update(['order' => $block['order']]);
                }
            }
            
            return response()->json([
                'status' => 'success',
                'message' => 'Content blocks order updated successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update content blocks order',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get active content blocks only.
     */
    public function getActive(string $projectId): JsonResponse
    {
        try {
            $project = Project::findOrFail($projectId);
            $contentBlocks = $project->contentBlocks()->with('images')->where('is_active', true)->orderBy('order')->get();
            
            return response()->json([
                'status' => 'success',
                'message' => 'Active project content blocks retrieved successfully',
                'data' => $contentBlocks,
                'count' => $contentBlocks->count()
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve active content blocks',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
