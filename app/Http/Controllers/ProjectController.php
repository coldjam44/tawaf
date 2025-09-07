<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Area;
use App\Models\RealEstateCompany;
use App\Models\ProjectDetail;
use App\Models\ProjectAmenity;
use App\Models\ProjectContentBlock;
use App\Models\ProjectContentBlockImage;
use App\Models\ProjectImage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

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
        
        // Search functionality
        if ($request->has('search') && $request->search) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('prj_title_ar', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('prj_title_en', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('prj_description_ar', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('prj_description_en', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('prj_projectNumber', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('prj_MadhmounPermitNumber', 'LIKE', "%{$searchTerm}%");
            });
        }
        
        $projects = $query->orderBy('created_at', 'desc')->paginate(10);
        $areas = Area::orderBy('name_en')->get();
        
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



    /**
     * Store a newly created project with all comprehensive details.
     */
    public function storeComprehensive(Request $request)
    {
        // Comprehensive validation
        $validator = Validator::make($request->all(), [
            // Basic project information
            'prj_title_ar' => 'required|string|max:255',
            'prj_title_en' => 'required|string|max:255',
            'prj_description_ar' => 'nullable|string',
            'prj_description_en' => 'nullable|string',
            'prj_areaId' => 'required|exists:areas,id',
            'company_id' => 'required|exists:real_estate_companies,id',
            'prj_adm' => 'nullable|string|max:255',
            'prj_cn' => 'nullable|string|max:255',
            'prj_projectNumber' => 'nullable|string|max:255',
            'prj_MadhmounPermitNumber' => 'nullable|string|max:255',
            
            // Files
            'prj_brochurefile' => 'nullable|file|mimes:pdf',
            'prj_floorplan' => 'nullable|file|mimes:pdf',
            
            // Project details
            'project_details' => 'nullable|array',
            'project_details.*.detail_ar' => 'required|string|max:255',
            'project_details.*.detail_en' => 'required|string|max:255', 
            'project_details.*.detail_value_ar' => 'required|string',
            'project_details.*.detail_value_en' => 'required|string',
            
            // Amenities
            'project_amenities' => 'nullable|array',
            'project_amenities.*.amenity_type' => 'required|string',
            'project_amenities.*.is_active' => 'required|boolean',
            
            // Content blocks
            'project_content_blocks' => 'nullable|array',
            'project_content_blocks.*.title_ar' => 'required|string|max:255',
            'project_content_blocks.*.title_en' => 'required|string|max:255',
            'project_content_blocks.*.content_ar' => 'required|string',
            'project_content_blocks.*.content_en' => 'required|string',
            'project_content_blocks.*.order' => 'required|integer',
            'project_content_blocks.*.images' => 'nullable|array',
            'project_content_blocks.*.images.*' => 'nullable|file|mimes:jpg,jpeg,png,gif',
            
            // Project images
            'project_images' => 'nullable|array',
            'project_images.interior' => 'nullable|array',
            'project_images.interior.*' => 'nullable|file|mimes:jpg,jpeg,png,gif',
            'project_images.exterior' => 'nullable|array', 
            'project_images.exterior.*' => 'nullable|file|mimes:jpg,jpeg,png,gif',
            'project_images.floorplan' => 'nullable|array',
            'project_images.floorplan.*' => 'nullable|file|mimes:jpg,jpeg,png,gif',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();
        
        try {
            // Create directories if they don't exist
            if (!file_exists(public_path('projects/brochures'))) {
                mkdir(public_path('projects/brochures'), 0755, true);
            }
            if (!file_exists(public_path('projects/floorplans'))) {
                mkdir(public_path('projects/floorplans'), 0755, true);
            }
            if (!file_exists(public_path('projects/images'))) {
                mkdir(public_path('projects/images'), 0755, true);
            }
            if (!file_exists(public_path('projects/content-blocks'))) {
                mkdir(public_path('projects/content-blocks'), 0755, true);
            }

            // Handle brochure file upload
            $brochureFileName = null;
            if ($request->hasFile('prj_brochurefile')) {
                $brochureFileName = time() . '_' . uniqid() . '.pdf';
                $request->prj_brochurefile->move(public_path('projects/brochures'), $brochureFileName);
            }

            // Handle floorplan file upload
            $floorplanFileName = null;
            if ($request->hasFile('prj_floorplan')) {
                $floorplanFileName = time() . '_' . uniqid() . '.pdf';
                $request->prj_floorplan->move(public_path('projects/floorplans'), $floorplanFileName);
            }

            // Create main project
            $project = Project::create([
                'prj_title_ar' => $request->prj_title_ar,
                'prj_title_en' => $request->prj_title_en,
                'prj_description_ar' => $request->prj_description_ar,
                'prj_description_en' => $request->prj_description_en,
                'prj_areaId' => $request->prj_areaId,
                'company_id' => $request->company_id,
                'prj_adm' => $request->prj_adm,
                'prj_cn' => $request->prj_cn,
                'prj_projectNumber' => $request->prj_projectNumber,
                'prj_MadhmounPermitNumber' => $request->prj_MadhmounPermitNumber,
                'prj_brochurefile' => $brochureFileName,
                'prj_floorplan' => $floorplanFileName,
            ]);

            // Create project details
            if ($request->has('project_details') && is_array($request->project_details)) {
                foreach ($request->project_details as $detailData) {
                    ProjectDetail::create([
                        'project_id' => $project->id,
                        'detail_ar' => $detailData['detail_ar'],
                        'detail_en' => $detailData['detail_en'],
                        'detail_value_ar' => $detailData['detail_value_ar'],
                        'detail_value_en' => $detailData['detail_value_en'],
                    ]);
                }
            }

            // Create amenities (all 10 types, either from request or default to inactive)
            $amenityTypes = [
                'swimming_pool', 'gym', 'parking', 'security', 'garden',
                'playground', 'elevator', 'balcony', 'air_conditioning', 'internet'
            ];
            
            if ($request->has('project_amenities') && is_array($request->project_amenities)) {
                foreach ($request->project_amenities as $amenityData) {
                    ProjectAmenity::create([
                        'project_id' => $project->id,
                        'amenity_type' => $amenityData['amenity_type'],
                        'is_active' => (bool) $amenityData['is_active']
                    ]);
                }
            } else {
                foreach ($amenityTypes as $type) {
                    ProjectAmenity::create([
                        'project_id' => $project->id,
                        'amenity_type' => $type,
                        'is_active' => false
                    ]);
                }
            }

            // Create content blocks
            if ($request->has('project_content_blocks') && is_array($request->project_content_blocks)) {
                foreach ($request->project_content_blocks as $index => $blockData) {
                    $contentBlock = ProjectContentBlock::create([
                        'project_id' => $project->id,
                        'title_ar' => $blockData['title_ar'],
                        'title_en' => $blockData['title_en'],
                        'content_ar' => $blockData['content_ar'],
                        'content_en' => $blockData['content_en'],
                        'order' => $blockData['order'] ?? $index + 1,
                    ]);

                    // Handle content block images
                    if (isset($blockData['images']) && is_array($blockData['images'])) {
                        foreach ($blockData['images'] as $image) {
                            if ($image && $image->isValid()) {
                                $imageFileName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                                $image->move(public_path('projects/content-blocks'), $imageFileName);
                                
                                ProjectContentBlockImage::create([
                                    'content_block_id' => $contentBlock->id,
                                    'image_path' => $imageFileName
                                ]);
                            }
                        }
                    }
                }
            }

            // Create project images
            if ($request->has('project_images') && is_array($request->project_images)) {
                foreach (['interior', 'exterior', 'floorplan'] as $imageType) {
                    if (isset($request->project_images[$imageType]) && is_array($request->project_images[$imageType])) {
                        foreach ($request->project_images[$imageType] as $image) {
                            if ($image && $image->isValid()) {
                                $imageFileName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                                $image->move(public_path('projects/images'), $imageFileName);
                                
                                ProjectImage::create([
                                    'project_id' => $project->id,
                                    'image_path' => $imageFileName,
                                    'image_type' => $imageType
                                ]);
                            }
                        }
                    }
                }
            }

            DB::commit();
            
            return redirect()->route('projects.index')->with('success', 'Project created successfully with all details!');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'An error occurred while creating the project: ' . $e->getMessage())->withInput();
        }
    }
}
