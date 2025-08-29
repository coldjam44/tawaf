<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProjectImage;
use App\Models\Project;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class ProjectImageController extends Controller
{
    /**
     * Display a listing of project images for a specific project.
     */
    public function index($projectId)
    {
        $project = Project::with(['projectImages' => function($query) {
            $query->orderBy('type', 'asc')->orderBy('order', 'asc');
        }])->findOrFail($projectId);
        
        return view('project-images.index', compact('project'));
    }

    /**
     * Show the form for creating a new project image.
     */
    public function create($projectId)
    {
        $project = Project::findOrFail($projectId);
        return view('project-images.create', compact('project'));
    }

    /**
     * Store a newly created project image.
     */
    public function store(Request $request, $projectId)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|in:interior,exterior,floorplan,featured',
            'images' => 'required|array|min:1|max:10',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB max per image
            'title_ar' => 'nullable|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'description_ar' => 'nullable|string',
            'description_en' => 'nullable|string',
            'order' => 'nullable|integer|min:0',
            'is_featured' => 'nullable|boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            // Create directory if it doesn't exist
            $uploadPath = public_path('projects/images');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            $uploadedCount = 0;
            $images = $request->file('images');

            // Get project details for auto-generated titles and descriptions
            $project = Project::find($projectId);
            $projectTitleAr = $project->prj_title_ar;
            $projectTitleEn = $project->prj_title_en;
            
            // Generate type labels
            $typeLabels = [
                'interior' => ['ar' => 'صور داخلية', 'en' => 'Interior Images'],
                'exterior' => ['ar' => 'صور خارجية', 'en' => 'Exterior Images'],
                'floorplan' => ['ar' => 'مخططات الطوابق', 'en' => 'Floor Plans'],
                'featured' => ['ar' => 'صور مميزة', 'en' => 'Featured Images']
            ];
            
            $typeLabelAr = $typeLabels[$request->type]['ar'];
            $typeLabelEn = $typeLabels[$request->type]['en'];

            foreach ($images as $image) {
                // Generate unique image name
                $imageName = time() . '_' . $projectId . '_' . $request->type . '_' . $uploadedCount . '.' . $image->getClientOriginalExtension();
                
                // Move image to directory
                $image->move($uploadPath, $imageName);

                // Generate auto titles and descriptions
                $autoTitleAr = $request->title_ar ?: $typeLabelAr . ' ' . $projectTitleAr;
                $autoTitleEn = $request->title_en ?: $typeLabelEn . ' ' . $projectTitleEn;
                $autoDescAr = $request->description_ar ?: 'مجموعة من ' . $typeLabelAr . ' لمشروع ' . $projectTitleAr;
                $autoDescEn = $request->description_en ?: 'Collection of ' . $typeLabelEn . ' for ' . $projectTitleEn . ' project';

                // Create project image record
                $projectImage = new ProjectImage();
                $projectImage->project_id = $projectId;
                $projectImage->type = $request->type;
                $projectImage->image_path = $imageName;
                $projectImage->title_ar = $autoTitleAr;
                $projectImage->title_en = $autoTitleEn;
                $projectImage->description_ar = $autoDescAr;
                $projectImage->description_en = $autoDescEn;
                $projectImage->order = ($request->order ?: 0) + $uploadedCount;
                $projectImage->is_featured = $request->has('is_featured');
                $projectImage->save();

                $uploadedCount++;
            }

            $message = $uploadedCount > 1 
                ? trans('main_trans.project_images_created_successfully', ['count' => $uploadedCount])
                : trans('main_trans.project_image_created_successfully');

            return redirect()->route('project-images.index', $projectId)
                           ->with('success', $message);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while creating the project image: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Show the form for editing the specified project image.
     */
    public function edit($projectId, $id)
    {
        $project = Project::findOrFail($projectId);
        $projectImage = ProjectImage::where('project_id', $projectId)->findOrFail($id);
        
        return view('project-images.edit', compact('project', 'projectImage'));
    }

    /**
     * Update the specified project image.
     */
    public function update(Request $request, $projectId, $id)
    {
        $projectImage = ProjectImage::where('project_id', $projectId)->findOrFail($id);

        $validator = Validator::make($request->all(), [
            'type' => 'required|in:interior,exterior,floorplan',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'title_ar' => 'nullable|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'description_ar' => 'nullable|string',
            'description_en' => 'nullable|string',
            'order' => 'nullable|integer|min:0',
            'is_featured' => 'nullable|boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            // Handle image upload if new image is provided
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '_' . $projectId . '_' . $request->type . '.' . $image->getClientOriginalExtension();
                
                // Delete old image
                if ($projectImage->image_path && file_exists(public_path('projects/images/' . $projectImage->image_path))) {
                    unlink(public_path('projects/images/' . $projectImage->image_path));
                }
                
                // Upload new image
                $uploadPath = public_path('projects/images');
                $image->move($uploadPath, $imageName);
                $projectImage->image_path = $imageName;
            }

            // Update other fields
            $projectImage->type = $request->type;
            $projectImage->title_ar = $request->title_ar;
            $projectImage->title_en = $request->title_en;
            $projectImage->description_ar = $request->description_ar;
            $projectImage->description_en = $request->description_en;
            $projectImage->order = $request->order ?: 0;
            $projectImage->is_featured = $request->has('is_featured');
            $projectImage->save();

            return redirect()->route('project-images.index', $projectId)
                           ->with('success', trans('main_trans.project_image_updated_successfully'));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while updating the project image: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified project image.
     */
    public function destroy($projectId, $id)
    {
        try {
            $projectImage = ProjectImage::where('project_id', $projectId)->findOrFail($id);
            
            // Delete image file
            if ($projectImage->image_path && file_exists(public_path('projects/images/' . $projectImage->image_path))) {
                unlink(public_path('projects/images/' . $projectImage->image_path));
            }
            
            $projectImage->delete();
            
            return redirect()->route('project-images.index', $projectId)
                           ->with('success', trans('main_trans.project_image_deleted_successfully'));
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while deleting the project image: ' . $e->getMessage());
        }
    }

    /**
     * Show project images in a public view.
     */
    public function show($projectId)
    {
        $project = Project::with(['projectImages' => function($query) {
            $query->orderBy('type', 'asc')->orderBy('order', 'asc');
        }, 'developer', 'area'])->findOrFail($projectId);
        
        return view('project-images.show', compact('project'));
    }
}
