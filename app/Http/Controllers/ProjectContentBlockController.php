<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectContentBlock;
use App\Models\ProjectContentBlockImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProjectContentBlockController extends Controller
{
    /**
     * Display a listing of project content blocks.
     */
    public function index($projectId)
    {
        $project = Project::findOrFail($projectId);
        $contentBlocks = $project->contentBlocks()->orderBy('order')->get();
        
        return view('project-content-blocks.index', compact('project', 'contentBlocks'));
    }

    /**
     * Show the form for creating a new content block.
     */
    public function create($projectId)
    {
        $project = Project::findOrFail($projectId);
        return view('project-content-blocks.create', compact('project'));
    }

    /**
     * Store a newly created content block.
     */
    public function store(Request $request, $projectId)
    {
        $project = Project::findOrFail($projectId);
        
        $request->validate([
            'title_ar' => 'required|string|max:255',
            'title_en' => 'required|string|max:255',
            'content_ar' => 'required|string',
            'content_en' => 'required|string',
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
            'is_active' => $request->has('is_active')
        ]);

        // Handle image uploads if any
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imageName = time() . '_' . uniqid() . '.' . $image->extension();
                $image->move(public_path('projects/content-blocks'), $imageName);

                ProjectContentBlockImage::create([
                    'content_block_id' => $contentBlock->id,
                    'image_path' => $imageName,
                    'title_ar' => 'صورة ' . ($contentBlock->images()->count() + 1),
                    'title_en' => 'Image ' . ($contentBlock->images()->count() + 1),
                    'order' => $contentBlock->images()->count(),
                    'is_active' => true
                ]);
            }
        }

        return redirect()->route('project-content-blocks.index', $projectId)
            ->with('success', 'تم إنشاء قسم المحتوى بنجاح');
    }

    /**
     * Display the specified content block.
     */
    public function show($projectId, $blockId)
    {
        $project = Project::findOrFail($projectId);
        $contentBlock = $project->contentBlocks()->findOrFail($blockId);
        
        return view('project-content-blocks.show', compact('project', 'contentBlock'));
    }

    /**
     * Show the form for editing the specified content block.
     */
    public function edit($projectId, $blockId)
    {
        $project = Project::findOrFail($projectId);
        $contentBlock = $project->contentBlocks()->findOrFail($blockId);
        
        return view('project-content-blocks.edit', compact('project', 'contentBlock'));
    }

    /**
     * Update the specified content block.
     */
    public function update(Request $request, $projectId, $blockId)
    {
        $project = Project::findOrFail($projectId);
        $contentBlock = $project->contentBlocks()->findOrFail($blockId);
        
        $request->validate([
            'title_ar' => 'required|string|max:255',
            'title_en' => 'required|string|max:255',
            'content_ar' => 'required|string',
            'content_en' => 'required|string',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean'
        ]);

        $updateData = [
            'title_ar' => $request->title_ar,
            'title_en' => $request->title_en,
            'content_ar' => $request->content_ar,
            'content_en' => $request->content_en,
            'order' => $request->order ?? $contentBlock->order,
            'is_active' => $request->has('is_active')
        ];
        
        $contentBlock->update($updateData);

        return redirect()->route('project-content-blocks.index', $projectId)
            ->with('success', 'تم تحديث قسم المحتوى بنجاح');
    }

    /**
     * Remove the specified content block.
     */
    public function destroy($projectId, $blockId)
    {
        $project = Project::findOrFail($projectId);
        $contentBlock = $project->contentBlocks()->findOrFail($blockId);
        
        // Delete associated images from database and server
        foreach ($contentBlock->images as $image) {
            if (file_exists(public_path('projects/content-blocks/' . $image->image_path))) {
                unlink(public_path('projects/content-blocks/' . $image->image_path));
            }
            $image->delete();
        }
        
        $contentBlock->delete();

        return redirect()->route('project-content-blocks.index', $projectId)
            ->with('success', 'تم حذف قسم المحتوى بنجاح');
    }

    /**
     * Toggle active status of content block.
     */
    public function toggle($projectId, $blockId)
    {
        $project = Project::findOrFail($projectId);
        $contentBlock = $project->contentBlocks()->findOrFail($blockId);
        
        $contentBlock->update(['is_active' => !$contentBlock->is_active]);
        
        $status = $contentBlock->is_active ? 'مفعل' : 'غير مفعل';
        
        return redirect()->route('project-content-blocks.index', $projectId)
            ->with('success', "تم تغيير حالة قسم المحتوى إلى: {$status}");
    }

    /**
     * Update order of content blocks.
     */
    public function updateOrder(Request $request, $projectId)
    {
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
        
        return response()->json(['success' => true, 'message' => 'تم تحديث الترتيب بنجاح']);
    }


}
