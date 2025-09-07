<?php

namespace App\Http\Controllers\Apis;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectContentBlock;
use App\Models\ProjectContentBlockImage;
use Illuminate\Http\Request;

class ProjectContentBlockImageController extends Controller
{
    /**
     * Display a listing of images for a content block.
     */
    public function index($projectId, $blockId)
    {
        try {
            $project = Project::findOrFail($projectId);
            $contentBlock = $project->contentBlocks()->findOrFail($blockId);
            $images = $contentBlock->images()->orderBy('order', 'asc')->get();

            return response()->json([
                'status' => 'success',
                'message' => 'تم جلب الصور بنجاح',
                'data' => $images
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'فشل في جلب الصور',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created image.
     */
    public function store(Request $request, $projectId, $blockId)
    {
        try {
            $project = Project::findOrFail($projectId);
            $contentBlock = $project->contentBlocks()->findOrFail($blockId);

            $request->validate([
                'images.*' => 'required|image|mimes:jpeg,png,jpg,gif',
                'title_ar' => 'nullable|string|max:255',
                'title_en' => 'nullable|string|max:255',
                'description_ar' => 'nullable|string',
                'description_en' => 'nullable|string',
                'order' => 'nullable|integer|min:0',
                'is_active' => 'nullable|boolean'
            ]);

            $uploadedImages = [];

            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $imageName = time() . '_' . uniqid() . '.' . $image->extension();
                    $image->move(public_path('projects/content-blocks'), $imageName);

                    $projectImage = ProjectContentBlockImage::create([
                        'content_block_id' => $contentBlock->id,
                        'image_path' => $imageName,
                        'title_ar' => $request->title_ar ?? 'صورة ' . ($contentBlock->images()->count() + 1),
                        'title_en' => $request->title_en ?? 'Image ' . ($contentBlock->images()->count() + 1),
                        'description_ar' => $request->description_ar,
                        'description_en' => $request->description_en,
                        'order' => $request->order ?? $contentBlock->images()->count(),
                        'is_active' => $request->has('is_active')
                    ]);

                    // Transform image_path to full URL
                    $projectImage->image_path = asset('projects/content-blocks/' . $projectImage->image_path);

                    $uploadedImages[] = $projectImage;
                }
            }

            return response()->json([
                'status' => 'success',
                'message' => 'تم رفع الصور بنجاح',
                'data' => $uploadedImages
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'فشل في رفع الصور',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified image.
     */
    public function show($projectId, $blockId, $imageId)
    {
        try {
            $project = Project::findOrFail($projectId);
            $contentBlock = $project->contentBlocks()->findOrFail($blockId);
            $image = $contentBlock->images()->findOrFail($imageId);

            return response()->json([
                'status' => 'success',
                'message' => 'تم جلب الصورة بنجاح',
                'data' => $image
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'فشل في جلب الصورة',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified image.
     */
    public function update(Request $request, $projectId, $blockId, $imageId)
    {
        try {
            $project = Project::findOrFail($projectId);
            $contentBlock = $project->contentBlocks()->findOrFail($blockId);
            $image = $contentBlock->images()->findOrFail($imageId);

            $request->validate([
                'title_ar' => 'nullable|string|max:255',
                'title_en' => 'nullable|string|max:255',
                'description_ar' => 'nullable|string',
                'description_en' => 'nullable|string',
                'order' => 'nullable|integer|min:0',
                'is_active' => 'nullable|boolean'
            ]);

            $image->update($request->only([
                'title_ar', 'title_en', 'description_ar', 'description_en', 'order', 'is_active'
            ]));

            return response()->json([
                'status' => 'success',
                'message' => 'تم تحديث الصورة بنجاح',
                'data' => $image->fresh()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'فشل في تحديث الصورة',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified image.
     */
    public function destroy($projectId, $blockId, $imageId)
    {
        try {
            $project = Project::findOrFail($projectId);
            $contentBlock = $project->contentBlocks()->findOrFail($blockId);
            $image = $contentBlock->images()->findOrFail($imageId);

            // Delete image file from server
            if (file_exists(public_path('projects/content-blocks/' . $image->image_path))) {
                unlink(public_path('projects/content-blocks/' . $image->image_path));
            }

            $image->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'تم حذف الصورة بنجاح'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'فشل في حذف الصورة',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle image active status.
     */
    public function toggle($projectId, $blockId, $imageId)
    {
        try {
            $project = Project::findOrFail($projectId);
            $contentBlock = $project->contentBlocks()->findOrFail($blockId);
            $image = $contentBlock->images()->findOrFail($imageId);

            $image->update(['is_active' => !$image->is_active]);

            return response()->json([
                'status' => 'success',
                'message' => 'تم تحديث حالة الصورة بنجاح',
                'data' => $image->fresh()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'فشل في تحديث حالة الصورة',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update image order.
     */
    public function updateOrder(Request $request, $projectId, $blockId)
    {
        try {
            $project = Project::findOrFail($projectId);
            $contentBlock = $project->contentBlocks()->findOrFail($blockId);

            $request->validate([
                'image_orders' => 'required|array',
                'image_orders.*' => 'integer|exists:project_content_block_images,id'
            ]);

            foreach ($request->image_orders as $order => $imageId) {
                $contentBlock->images()->where('id', $imageId)->update(['order' => $order]);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'تم تحديث الترتيب بنجاح'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'فشل في تحديث الترتيب',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
