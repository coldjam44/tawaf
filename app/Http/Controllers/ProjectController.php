<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Area;
use Illuminate\Support\Facades\Validator;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request) {
        $query = Project::with(['developer', 'area', 'company']);
        
        // Filter by area if provided
        if ($request->has('area') && $request->area) {
            $query->where('prj_areaId', $request->area);
        }
        
        $projects = $query->paginate(10);
        $areas = Area::all();
        
        return view('projects.index', compact('projects', 'areas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validation for bilingual fields
        $validator = Validator::make($request->all(), [
            'prj_title_ar' => 'required|string|max:255',
            'prj_title_en' => 'required|string|max:255',
            'prj_description_ar' => 'nullable|string',
            'prj_description_en' => 'nullable|string',
            'prj_brochurefile' => 'nullable|file|mimes:pdf,doc,docx|max:5120', // 5MB max
            'prj_areaId' => 'required|exists:areas,id',
            'company_id' => 'required|exists:real_estate_companies,id',
            'prj_adm' => 'nullable|string|max:255',
            'prj_cn' => 'nullable|string|max:255',
            'prj_projectNumber' => 'nullable|string|max:255',
            'prj_MadhmounPermitNumber' => 'nullable|string|max:255',
            'prj_floorplan' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            // Handle brochure file upload
            $brochureFileName = null;
            if ($request->hasFile('prj_brochurefile')) {
                $brochureFileName = time() . '_brochure.' . $request->prj_brochurefile->extension();
                $request->prj_brochurefile->move(public_path('projects/brochures'), $brochureFileName);
            }

            // Handle floorplan file upload
            $floorplanFileName = null;
            if ($request->hasFile('prj_floorplan')) {
                $floorplanFileName = time() . '_floorplan.' . $request->prj_floorplan->extension();
                $request->prj_floorplan->move(public_path('projects/floorplans'), $floorplanFileName);
            }

            // Create project with bilingual fields
            $project = new Project();
            $project->prj_title_ar = $request->prj_title_ar;
            $project->prj_title_en = $request->prj_title_en;
            $project->prj_description_ar = $request->prj_description_ar;
            $project->prj_description_en = $request->prj_description_en;
            $project->prj_brochurefile = $brochureFileName;
            $project->prj_areaId = $request->prj_areaId;
            $project->company_id = $request->company_id;
            $project->prj_adm = $request->prj_adm;
            $project->prj_cn = $request->prj_cn;
            $project->prj_projectNumber = $request->prj_projectNumber;
            $project->prj_MadhmounPermitNumber = $request->prj_MadhmounPermitNumber;
            $project->prj_floorplan = $floorplanFileName;
            $project->save();

            return redirect()->route('projects.index')->with('success', trans('main_trans.project_created_successfully'));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while creating the project: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $project = Project::with(['area', 'company'])->findOrFail($id);
        $areas = Area::all();
        return view('projects.edit', compact('project', 'areas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $project = Project::findOrFail($id);

        // Validation for bilingual fields
        $validator = Validator::make($request->all(), [
            'prj_title_ar' => 'required|string|max:255',
            'prj_title_en' => 'required|string|max:255',
            'prj_description_ar' => 'nullable|string',
            'prj_description_en' => 'nullable|string',
            'prj_brochurefile' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'prj_areaId' => 'required|exists:areas,id',
            'company_id' => 'required|exists:real_estate_companies,id',
            'prj_adm' => 'nullable|string|max:255',
            'prj_cn' => 'nullable|string|max:255',
            'prj_projectNumber' => 'nullable|string|max:255',
            'prj_MadhmounPermitNumber' => 'nullable|string|max:255',
            'prj_floorplan' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            // Handle brochure file upload
            if ($request->hasFile('prj_brochurefile')) {
                // Delete old file if exists
                if ($project->prj_brochurefile && file_exists(public_path('projects/brochures/' . $project->prj_brochurefile))) {
                    unlink(public_path('projects/brochures/' . $project->prj_brochurefile));
                }
                $brochureFileName = time() . '_brochure.' . $request->prj_brochurefile->extension();
                $request->prj_brochurefile->move(public_path('projects/brochures'), $brochureFileName);
                $project->prj_brochurefile = $brochureFileName;
            }

            // Handle floorplan file upload
            if ($request->hasFile('prj_floorplan')) {
                // Delete old file if exists
                if ($project->prj_floorplan && file_exists(public_path('projects/floorplans/' . $project->prj_floorplan))) {
                    unlink(public_path('projects/floorplans/' . $project->prj_floorplan));
                }
                $floorplanFileName = time() . '_floorplan.' . $request->prj_floorplan->extension();
                $request->prj_floorplan->move(public_path('projects/floorplans'), $floorplanFileName);
                $project->prj_floorplan = $floorplanFileName;
            }

            // Update project with bilingual fields
            $project->prj_title_ar = $request->prj_title_ar;
            $project->prj_title_en = $request->prj_title_en;
            $project->prj_description_ar = $request->prj_description_ar;
            $project->prj_description_en = $request->prj_description_en;
            $project->prj_areaId = $request->prj_areaId;
            $project->company_id = $request->company_id;
            $project->prj_adm = $request->prj_adm;
            $project->prj_cn = $request->prj_cn;
            $project->prj_projectNumber = $request->prj_projectNumber;
            $project->prj_MadhmounPermitNumber = $request->prj_MadhmounPermitNumber;
            $project->save();

            return redirect()->route('projects.index')->with('success', trans('main_trans.project_updated_successfully'));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while updating the project: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $project = Project::findOrFail($id);
            
            // Delete associated files
            if ($project->prj_brochurefile && file_exists(public_path('projects/brochures/' . $project->prj_brochurefile))) {
                unlink(public_path('projects/brochures/' . $project->prj_brochurefile));
            }
            
            if ($project->prj_floorplan && file_exists(public_path('projects/floorplans/' . $project->prj_floorplan))) {
                unlink(public_path('projects/floorplans/' . $project->prj_floorplan));
            }
            
            $project->delete();
            
            return redirect()->route('projects.index')->with('success', trans('main_trans.project_deleted_successfully'));
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while deleting the project: ' . $e->getMessage());
        }
    }
}
