<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProjectDescription;
use App\Models\Project;
use Illuminate\Support\Facades\Validator;

class ProjectDescriptionController extends Controller
{
    /**
     * Display a listing of project descriptions for a specific project.
     */
    public function index($projectId)
    {
        $project = Project::with(['descriptions' => function($query) {
            $query->orderBy('order_index', 'asc');
        }])->findOrFail($projectId);
        
        return view('project-descriptions.index', compact('project'));
    }

    /**
     * Show the form for creating a new project description.
     */
    public function create($projectId)
    {
        $project = Project::findOrFail($projectId);
        $sectionTypes = ProjectDescription::SECTION_TYPES;
        
        return view('project-descriptions.create', compact('project', 'sectionTypes'));
    }

    /**
     * Store a newly created project description.
     */
    public function store(Request $request, $projectId)
    {
        $validator = Validator::make($request->all(), [
            'section_type' => 'required|in:about,architecture,why_choose,location,investment,location_map',
            'title_ar' => 'required|string|max:255',
            'title_en' => 'required|string|max:255',
            'content_ar' => 'required|string',
            'content_en' => 'required|string',
            'location_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB max
            'google_location' => 'nullable|string|max:500',
            'location_address_ar' => 'nullable|string|max:255',
            'location_address_en' => 'nullable|string|max:255',
            'order_index' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            // Handle location image upload if provided
            $locationImageName = null;
            if ($request->hasFile('location_image')) {
                $image = $request->file('location_image');
                $locationImageName = time() . '_' . $projectId . '_location.' . $image->getClientOriginalExtension();
                
                // Create directory if it doesn't exist
                $uploadPath = public_path('projects/location-images');
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }
                
                $image->move($uploadPath, $locationImageName);
            }

            $projectDescription = new ProjectDescription();
            $projectDescription->project_id = $projectId;
            $projectDescription->section_type = $request->section_type;
            $projectDescription->title_ar = $request->title_ar;
            $projectDescription->title_en = $request->title_en;
            $projectDescription->content_ar = $request->content_ar;
            $projectDescription->content_en = $request->content_en;
            $projectDescription->location_image = $locationImageName;
            $projectDescription->google_location = $request->google_location;
            $projectDescription->location_address_ar = $request->location_address_ar;
            $projectDescription->location_address_en = $request->location_address_en;
            $projectDescription->order_index = $request->order_index ?: 0;
            $projectDescription->is_active = $request->has('is_active');
            $projectDescription->save();

            return redirect()->route('project-descriptions.index', $projectId)
                           ->with('success', trans('main_trans.project_description_created_successfully'));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while creating the project description: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Show the form for editing the specified project description.
     */
    public function edit($projectId, $id)
    {
        $project = Project::findOrFail($projectId);
        $projectDescription = ProjectDescription::where('project_id', $projectId)->findOrFail($id);
        $sectionTypes = ProjectDescription::SECTION_TYPES;
        
        return view('project-descriptions.edit', compact('project', 'projectDescription', 'sectionTypes'));
    }

    /**
     * Update the specified project description.
     */
    public function update(Request $request, $projectId, $id)
    {
        $projectDescription = ProjectDescription::where('project_id', $projectId)->findOrFail($id);

        $validator = Validator::make($request->all(), [
            'section_type' => 'required|in:about,architecture,why_choose,location,investment,location_map',
            'title_ar' => 'required|string|max:255',
            'title_en' => 'required|string|max:255',
            'content_ar' => 'required|string',
            'content_en' => 'required|string',
            'location_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB max
            'google_location' => 'nullable|string|max:500',
            'location_address_ar' => 'nullable|string|max:255',
            'location_address_en' => 'nullable|string|max:255',
            'order_index' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            // Handle location image upload if provided
            if ($request->hasFile('location_image')) {
                // Delete old image if exists
                if ($projectDescription->location_image) {
                    $oldImagePath = public_path('projects/location-images/' . $projectDescription->location_image);
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }

                $image = $request->file('location_image');
                $locationImageName = time() . '_' . $projectId . '_location.' . $image->getClientOriginalExtension();
                
                // Create directory if it doesn't exist
                $uploadPath = public_path('projects/location-images');
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }
                
                $image->move($uploadPath, $locationImageName);
                $projectDescription->location_image = $locationImageName;
            }

            $projectDescription->section_type = $request->section_type;
            $projectDescription->title_ar = $request->title_ar;
            $projectDescription->title_en = $request->title_en;
            $projectDescription->content_ar = $request->content_ar;
            $projectDescription->content_en = $request->content_en;
            $projectDescription->google_location = $request->google_location;
            $projectDescription->location_address_ar = $request->location_address_ar;
            $projectDescription->location_address_en = $request->location_address_en;
            $projectDescription->order_index = $request->order_index ?: 0;
            $projectDescription->is_active = $request->has('is_active');
            $projectDescription->save();

            return redirect()->route('project-descriptions.index', $projectId)
                           ->with('success', trans('main_trans.project_description_updated_successfully'));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while updating the project description: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified project description.
     */
    public function destroy($projectId, $id)
    {
        try {
            $projectDescription = ProjectDescription::where('project_id', $projectId)->findOrFail($id);
            
            // Delete location image if exists
            if ($projectDescription->location_image) {
                $imagePath = public_path('projects/location-images/' . $projectDescription->location_image);
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }
            
            $projectDescription->delete();
            
            return redirect()->route('project-descriptions.index', $projectId)
                           ->with('success', trans('main_trans.project_description_deleted_successfully'));
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while deleting the project description: ' . $e->getMessage());
        }
    }

    /**
     * Show project descriptions in a public view.
     */
    public function show($projectId)
    {
        $project = Project::with(['descriptions' => function($query) {
            $query->where('is_active', true)->orderBy('order_index', 'asc');
        }, 'developer', 'area'])->findOrFail($projectId);
        
        return view('project-descriptions.show', compact('project'));
    }
}
