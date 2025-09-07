<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectContentBlock;
use App\Models\ProjectContentBlockImage;
use Illuminate\Http\Request;

class ProjectContentBlockImageController extends Controller
{
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

                    $uploadedImages[] = $projectImage;
                }
            }

            return redirect()->route('project-content-blocks.edit', [$projectId, $blockId])
                ->with('success', 'تم رفع الصور بنجاح');

        } catch (\Exception $e) {
            return redirect()->route('project-content-blocks.edit', [$projectId, $blockId])
                ->with('error', 'حدث خطأ أثناء رفع الصور');
        }
    }

    /**
     * Delete a specific image.
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

            return redirect()->route('project-content-blocks.edit', [$projectId, $blockId])
                ->with('success', 'تم حذف الصورة بنجاح');

        } catch (\Exception $e) {
            return redirect()->route('project-content-blocks.edit', [$projectId, $blockId])
                ->with('error', 'حدث خطأ أثناء حذف الصورة');
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

            return redirect()->route('project-content-blocks.edit', [$projectId, $blockId])
                ->with('success', 'تم تحديث حالة الصورة بنجاح');

        } catch (\Exception $e) {
            return redirect()->route('project-content-blocks.edit', [$projectId, $blockId])
                ->with('error', 'حدث خطأ أثناء تحديث حالة الصورة');
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

            return response()->json(['success' => true, 'message' => 'تم تحديث الترتيب بنجاح']);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'حدث خطأ أثناء تحديث الترتيب']);
        }
    }
}
