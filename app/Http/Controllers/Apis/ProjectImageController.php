<?php

namespace App\Http\Controllers\Apis;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectImage;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ProjectImageController extends Controller
{
    /**
     * Display a listing of project images.
     */
    public function index(string $projectId): JsonResponse
    {
        try {
            $project = Project::findOrFail($projectId);
            $images = $project->projectImages()->orderBy('order')->get();
            
            // Add full URL to each image
            $imagesWithUrls = $images->map(function ($image) {
                $image->image_url = url('projects/images/' . $image->image_path);
                return $image;
            });
            
            return response()->json([
                'status' => 'success',
                'message' => 'Project images retrieved successfully',
                'data' => $imagesWithUrls,
                'project' => [
                    'id' => $project->id,
                    'name' => $project->name
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve project images',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created project image.
     */
    public function store(Request $request, string $projectId): JsonResponse
    {
        try {
            $project = Project::findOrFail($projectId);
            
            $request->validate([
                'type' => 'required|in:interior,exterior,floorplan,featured',
                'images' => 'required|array|max:10',
                'images.*' => 'image|mimes:jpeg,png,jpg,gif',
            ]);

            $uploadedImages = [];
            
            foreach ($request->file('images') as $image) {
                $imageName = time() . '_' . uniqid() . '.' . $image->extension();
                $image->move(public_path('projects/images'), $imageName);
                
                // Get the next order number for this project and type
                $nextOrder = $project->projectImages()->where('type', $request->type)->max('order') + 1;
                
                $projectImage = $project->projectImages()->create([
                    'type' => $request->type,
                    'image_path' => $imageName,
                    'title_ar' => 'صورة ' . $request->type . ' ' . $nextOrder,
                    'title_en' => ucfirst($request->type) . ' Image ' . $nextOrder,
                    'description_ar' => 'صورة ' . $request->type . ' للمشروع',
                    'description_en' => ucfirst($request->type) . ' image for the project',
                    'order' => $nextOrder,
                    'is_featured' => false,
                ]);
                
                // Add full URL to the image
                $projectImage->image_url = url('projects/images/' . $imageName);
                $uploadedImages[] = $projectImage;
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Project images uploaded successfully',
                'data' => $uploadedImages,
                'count' => count($uploadedImages)
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to upload project images',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified project image.
     */
    public function show(string $projectId, string $imageId): JsonResponse
    {
        try {
            $project = Project::findOrFail($projectId);
            $image = $project->projectImages()->findOrFail($imageId);
            
            // Add full URL to the image
            $image->image_url = url('projects/images/' . $image->image_path);
            
            return response()->json([
                'status' => 'success',
                'message' => 'Project image retrieved successfully',
                'data' => $image
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Project image not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Update the specified project image.
     */
    public function update(Request $request, string $projectId, string $imageId): JsonResponse
    {
        try {
            $project = Project::findOrFail($projectId);
            $image = $project->projectImages()->findOrFail($imageId);
            
            $request->validate([
                'type' => 'sometimes|required|in:interior,exterior,floorplan,featured',
                'title_ar' => 'nullable|string|max:255',
                'title_en' => 'nullable|string|max:255',
                'description_ar' => 'nullable|string',
                'description_en' => 'nullable|string',
                'order' => 'nullable|integer|min:0',
                'is_featured' => 'nullable|boolean',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            ]);

            $image->update($request->except(['image']));

            // Handle image upload if provided
            if ($request->hasFile('image')) {
                // Delete old image
                if ($image->image_path && file_exists(public_path('projects/images/' . $image->image_path))) {
                    unlink(public_path('projects/images/' . $image->image_path));
                }
                
                $imageFile = $request->file('image');
                $imageName = time() . '_' . uniqid() . '.' . $imageFile->extension();
                $imageFile->move(public_path('projects/images'), $imageName);
                
                $image->update(['image_path' => $imageName]);
            }

            $updatedImage = $image->fresh();
            // Add full URL to the updated image
            $updatedImage->image_url = url('projects/images/' . $updatedImage->image_path);
            
            return response()->json([
                'status' => 'success',
                'message' => 'Project image updated successfully',
                'data' => $updatedImage
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update project image',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified project image.
     */
    public function destroy(string $projectId, string $imageId): JsonResponse
    {
        try {
            $project = Project::findOrFail($projectId);
            $image = $project->projectImages()->findOrFail($imageId);
            
            // Delete image file
            if ($image->image_path && file_exists(public_path('projects/images/' . $image->image_path))) {
                unlink(public_path('projects/images/' . $image->image_path));
            }
            
            $image->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Project image deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete project image',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get images by type.
     */
    public function getByType(string $projectId, string $type): JsonResponse
    {
        try {
            $project = Project::findOrFail($projectId);
            $images = $project->projectImages()->where('type', $type)->orderBy('order')->get();
            
            // Add full URL to each image
            $imagesWithUrls = $images->map(function ($image) {
                $image->image_url = url('projects/images/' . $image->image_path);
                return $image;
            });
            
            return response()->json([
                'status' => 'success',
                'message' => "Project {$type} images retrieved successfully",
                'data' => $imagesWithUrls,
                'type' => $type,
                'count' => $images->count()
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve project images by type',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get featured images.
     */
    public function getFeatured(string $projectId): JsonResponse
    {
        try {
            $project = Project::findOrFail($projectId);
            $images = $project->projectImages()->where('is_featured', true)->orderBy('order')->get();
            
            // Add full URL to each image
            $imagesWithUrls = $images->map(function ($image) {
                $image->image_url = url('projects/images/' . $image->image_path);
                return $image;
            });
            
            return response()->json([
                'status' => 'success',
                'message' => 'Project featured images retrieved successfully',
                'data' => $imagesWithUrls,
                'count' => $images->count()
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve featured images',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
