<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProjectDetail;
use App\Models\Project;
use Illuminate\Support\Facades\Validator;

class ProjectDetailController extends Controller
{
    /**
     * Display a listing of project details for a specific project.
     */
    public function index($projectId)
    {
        $project = Project::with(['projectDetails' => function($query) {
            $query->orderBy('order', 'asc');
        }])->findOrFail($projectId);
        
        return view('project-details.index', compact('project'));
    }

    /**
     * Show the form for creating a new project detail.
     */
    public function create($projectId)
    {
        $project = Project::findOrFail($projectId);
        return view('project-details.create', compact('project'));
    }

    /**
     * Store a newly created project detail.
     */
    public function store(Request $request, $projectId)
    {
        $validator = Validator::make($request->all(), [
            'detail_ar' => 'required|string|max:255',
            'detail_en' => 'required|string|max:255',
            'detail_value_ar' => 'required|string',
            'detail_value_en' => 'required|string',
            'order' => 'nullable|integer|min:0'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $projectDetail = new ProjectDetail();
            $projectDetail->project_id = $projectId;
            $projectDetail->detail_ar = $request->detail_ar;
            $projectDetail->detail_en = $request->detail_en;
            $projectDetail->detail_value_ar = $request->detail_value_ar;
            $projectDetail->detail_value_en = $request->detail_value_en;
            $projectDetail->order = $request->order ?: 0;
            $projectDetail->save();

            return redirect()->route('project-details.index', $projectId)
                           ->with('success', trans('main_trans.project_detail_created_successfully'));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while creating the project detail: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Show the form for editing the specified project detail.
     */
    public function edit($projectId, $id)
    {
        $project = Project::findOrFail($projectId);
        $projectDetail = ProjectDetail::where('project_id', $projectId)->findOrFail($id);
        
        return view('project-details.edit', compact('project', 'projectDetail'));
    }

    /**
     * Update the specified project detail.
     */
    public function update(Request $request, $projectId, $id)
    {
        $projectDetail = ProjectDetail::where('project_id', $projectId)->findOrFail($id);

        $validator = Validator::make($request->all(), [
            'detail_ar' => 'required|string|max:255',
            'detail_en' => 'required|string|max:255',
            'detail_value_ar' => 'required|string',
            'detail_value_en' => 'required|string',
            'order' => 'nullable|integer|min:0'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $projectDetail->detail_ar = $request->detail_ar;
            $projectDetail->detail_en = $request->detail_en;
            $projectDetail->detail_value_ar = $request->detail_value_ar;
            $projectDetail->detail_value_en = $request->detail_value_en;
            $projectDetail->order = $request->order ?: 0;
            $projectDetail->save();

            return redirect()->route('project-details.index', $projectId)
                           ->with('success', trans('main_trans.project_detail_updated_successfully'));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while updating the project detail: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified project detail.
     */
    public function destroy($projectId, $id)
    {
        try {
            $projectDetail = ProjectDetail::where('project_id', $projectId)->findOrFail($id);
            $projectDetail->delete();
            
            return redirect()->route('project-details.index', $projectId)
                           ->with('success', trans('main_trans.project_detail_deleted_successfully'));
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while deleting the project detail: ' . $e->getMessage());
        }
    }

    /**
     * Show project details in a public view.
     */
    public function show($projectId)
    {
        $project = Project::with(['projectDetails' => function($query) {
            $query->orderBy('order', 'asc');
        }, 'developer', 'area'])->findOrFail($projectId);
        
        return view('project-details.show', compact('project'));
    }
}
