<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProjectAmenity;
use App\Models\Project;
use Illuminate\Support\Facades\Validator;

class ProjectAmenityController extends Controller
{
    /**
     * Display a listing of project amenities for a specific project.
     */
    public function index($projectId)
    {
        $project = Project::with('amenities')->findOrFail($projectId);
        $amenityTypes = ProjectAmenity::getAmenityTypesData();
        
        // Get all available amenity types and check if they exist for this project
        $allAmenities = [];
        foreach ($amenityTypes as $type => $data) {
            $existingAmenity = $project->amenities->where('amenity_type', $type)->first();
            $allAmenities[$type] = [
                'data' => $data,
                'exists' => $existingAmenity ? true : false,
                'is_active' => $existingAmenity ? $existingAmenity->is_active : false,
                'id' => $existingAmenity ? $existingAmenity->id : null
            ];
        }
        
        return view('project-amenities.index', compact('project', 'allAmenities'));
    }

    /**
     * Store a new project amenity.
     */
    public function store(Request $request, $projectId)
    {
        $validator = Validator::make($request->all(), [
            'amenity_type' => 'required|in:' . implode(',', ProjectAmenity::getAvailableTypes()),
            'is_active' => 'nullable|boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            // Check if amenity already exists for this project
            $existingAmenity = ProjectAmenity::where('project_id', $projectId)
                                            ->where('amenity_type', $request->amenity_type)
                                            ->first();

            if ($existingAmenity) {
                return redirect()->back()->with('error', 'This amenity already exists for this project.');
            }

            $projectAmenity = new ProjectAmenity();
            $projectAmenity->project_id = $projectId;
            $projectAmenity->amenity_type = $request->amenity_type;
            $projectAmenity->is_active = $request->has('is_active');
            $projectAmenity->save();

            return redirect()->route('project-amenities.index', $projectId)
                           ->with('success', trans('main_trans.project_amenity_created_successfully'));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while creating the project amenity: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Toggle amenity status (activate/deactivate).
     */
    public function toggle($projectId, $id)
    {
        try {
            $projectAmenity = ProjectAmenity::where('project_id', $projectId)->findOrFail($id);
            $projectAmenity->is_active = !$projectAmenity->is_active;
            $projectAmenity->save();

            $status = $projectAmenity->is_active ? 'activated' : 'deactivated';
            $message = trans('main_trans.project_amenity_' . $status . '_successfully');

            return redirect()->route('project-amenities.index', $projectId)
                           ->with('success', $message);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while updating the amenity status: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified project amenity.
     */
    public function update(Request $request, $projectId, $id)
    {
        $validator = Validator::make($request->all(), [
            'is_active' => 'required|boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $projectAmenity = ProjectAmenity::where('project_id', $projectId)->findOrFail($id);
            $projectAmenity->is_active = $request->is_active;
            $projectAmenity->save();

            return redirect()->route('project-amenities.index', $projectId)
                           ->with('success', trans('main_trans.project_amenity_updated_successfully'));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while updating the project amenity: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified project amenity.
     */
    public function destroy($projectId, $id)
    {
        try {
            $projectAmenity = ProjectAmenity::where('project_id', $projectId)->findOrFail($id);
            $projectAmenity->delete();
            
            return redirect()->route('project-amenities.index', $projectId)
                           ->with('success', trans('main_trans.project_amenity_deleted_successfully'));
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while deleting the project amenity: ' . $e->getMessage());
        }
    }

    /**
     * Bulk update amenities for a project.
     */
    public function bulkUpdate(Request $request, $projectId)
    {
        $validator = Validator::make($request->all(), [
            'amenities' => 'required|array',
            'amenities.*' => 'in:' . implode(',', ProjectAmenity::getAvailableTypes())
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            // Get all amenity types
            $allTypes = ProjectAmenity::getAvailableTypes();
            
            foreach ($allTypes as $type) {
                $isActive = in_array($type, $request->amenities);
                
                // Find existing amenity or create new one
                $amenity = ProjectAmenity::firstOrNew([
                    'project_id' => $projectId,
                    'amenity_type' => $type
                ]);
                
                $amenity->is_active = $isActive;
                $amenity->save();
            }

            return redirect()->route('project-amenities.index', $projectId)
                           ->with('success', trans('main_trans.project_amenities_updated_successfully'));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while updating amenities: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Show project amenities in a public view.
     */
    public function show($projectId)
    {
        $project = Project::with(['amenities' => function($query) {
            $query->where('is_active', true);
        }, 'developer', 'area'])->findOrFail($projectId);
        
        return view('project-amenities.show', compact('project'));
    }
}
