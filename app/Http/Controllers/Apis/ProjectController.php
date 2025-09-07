<?php

namespace App\Http\Controllers\Apis;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Area;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        try {
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
            
            $projects = $query->paginate($request->get('per_page', 10));
            $areas = Area::all();
            
            return response()->json([
                'status' => 'success',
                'message' => 'Projects retrieved successfully',
                'data' => $projects,
                'areas' => $areas
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve projects',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $request->validate([
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

            $project = new Project();
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

            // Handle brochure file upload
            if ($request->hasFile('prj_brochurefile')) {
                $brochureFileName = time() . '_brochure.' . $request->prj_brochurefile->extension();
                $request->prj_brochurefile->move(public_path('projects/brochures'), $brochureFileName);
                $project->prj_brochurefile = $brochureFileName;
            }

            // Handle floorplan file upload
            if ($request->hasFile('prj_floorplan')) {
                $floorplanFileName = time() . '_floorplan.' . $request->prj_floorplan->extension();
                $request->prj_floorplan->move(public_path('projects/floorplans'), $floorplanFileName);
                $project->prj_floorplan = $floorplanFileName;
            }

            $project->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Project created successfully',
                'data' => $project->load(['developer', 'area', 'company'])
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create project',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        try {
            $project = Project::with(['developer', 'area', 'company'])->findOrFail($id);
            
            return response()->json([
                'status' => 'success',
                'message' => 'Project retrieved successfully',
                'data' => $project
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Project not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        try {
            $project = Project::findOrFail($id);
            
            $request->validate([
                'prj_title_ar' => 'sometimes|required|string|max:255',
                'prj_title_en' => 'sometimes|required|string|max:255',
                'prj_description_ar' => 'nullable|string',
                'prj_description_en' => 'nullable|string',
                'prj_brochurefile' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
                'prj_areaId' => 'sometimes|required|exists:areas,id',
                'company_id' => 'sometimes|required|exists:real_estate_companies,id',
                'prj_adm' => 'nullable|string|max:255',
                'prj_cn' => 'nullable|string|max:255',
                'prj_projectNumber' => 'nullable|string|max:255',
                'prj_MadhmounPermitNumber' => 'nullable|string|max:255',
                'prj_floorplan' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            ]);

            $project->update($request->except(['prj_brochurefile', 'prj_floorplan']));

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

            $project->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Project updated successfully',
                'data' => $project->load(['developer', 'area', 'company'])
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update project',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $project = Project::findOrFail($id);
            
            // Delete files if exist
            if ($project->prj_brochurefile && file_exists(public_path('projects/brochures/' . $project->prj_brochurefile))) {
                unlink(public_path('projects/brochures/' . $project->prj_brochurefile));
            }
            
            if ($project->prj_floorplan && file_exists(public_path('projects/floorplans/' . $project->prj_floorplan))) {
                unlink(public_path('projects/floorplans/' . $project->prj_floorplan));
            }
            
            $project->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Project deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete project',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Search projects
     */
    public function search(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'q' => 'required|string|min:2',
                'per_page' => 'nullable|integer|min:1|max:100'
            ]);

            $query = Project::with(['developer', 'area', 'company']);
            $searchTerm = $request->q;
            
            $query->where(function($q) use ($searchTerm) {
                $q->where('prj_title_ar', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('prj_title_en', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('prj_description_ar', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('prj_description_en', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('prj_projectNumber', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('prj_MadhmounPermitNumber', 'LIKE', "%{$searchTerm}%");
            });

            $projects = $query->paginate($request->get('per_page', 10));

            return response()->json([
                'status' => 'success',
                'message' => 'Search completed successfully',
                'data' => $projects,
                'search_term' => $searchTerm
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Search failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all projects with comprehensive details
     */
    public function getAllWithDetails(Request $request): JsonResponse
    {
        try {
            $projects = Project::all();
            
            // Transform each project to add organized images and project details
            $projects->transform(function ($project) {
                // Add area information with only name_en and name_ar (before images)
                if ($project->prj_areaId) {
                    $area = \App\Models\Area::find($project->prj_areaId);
                    if ($area) {
                        $project->area = [
                            'name_en' => $area->name_en,
                            'name_ar' => $area->name_ar
                        ];
                    }
                }
                
                // Add real estate company information with only company_name_ar and company_name_en
                if ($project->company_id) {
                    $company = \App\Models\RealEstateCompany::find($project->company_id);
                    if ($company) {
                        $project->company = [
                            'company_name_ar' => $company->company_name_ar,
                            'company_name_en' => $company->company_name_en
                        ];
                    } else {
                        $project->company = [
                            'company_name_ar' => null,
                            'company_name_en' => null
                        ];
                    }
                } else {
                    $project->company = [
                        'company_name_ar' => null,
                        'company_name_en' => null
                    ];
                }
                
                // Load project images for this specific project
                $projectImages = \App\Models\ProjectImage::where('project_id', $project->id)->get();
                
                // Organize images by type - only image_path URLs
                $project->images = [
                    'interior' => $projectImages->where('type', 'interior')->pluck('image_path')->map(function ($path) {
                        return asset('projects/images/' . $path);
                    })->values(),
                    'exterior' => $projectImages->where('type', 'exterior')->pluck('image_path')->map(function ($path) {
                        return asset('projects/images/' . $path);
                    })->values(),
                    'floorplan' => $projectImages->where('type', 'floorplan')->pluck('image_path')->map(function ($path) {
                        return asset('projects/images/' . $path);
                    })->values()
                ];
                
                // Load project details for this specific project
                $projectDetails = \App\Models\ProjectDetail::where('project_id', $project->id)->orderBy('order')->get();
                
                // Add project details object
                $project->ProjectDetails = $projectDetails->map(function ($detail) {
                    return [
                        'detail_ar' => $detail->detail_ar,
                        'detail_en' => $detail->detail_en,
                        'detail_value_ar' => $detail->detail_value_ar,
                        'detail_value_en' => $detail->detail_value_en
                    ];
                });
                
                // Load project amenities for this specific project
                $projectAmenities = \App\Models\ProjectAmenity::where('project_id', $project->id)->get();
                
                // Add amenities object
                $project->Amenities = $projectAmenities->map(function ($amenity) {
                    return [
                        'amenity_type' => $amenity->amenity_type,
                        'is_active' => $amenity->is_active
                    ];
                });
                
                // Load project content blocks with their images for this specific project
                $projectContentBlocks = \App\Models\ProjectContentBlock::with('images')->where('project_id', $project->id)->orderBy('order')->get();
                
                // Add project content blocks object
                $project->project_content_blocks = $projectContentBlocks->map(function ($block) {
                    return [
                        'order' => $block->order,
                        'title_ar' => $block->title_ar,
                        'title_en' => $block->title_en,
                        'content_ar' => $block->content_ar,
                        'content_en' => $block->content_en,
                        'images' => $block->images->map(function ($image) {
                            return asset('projects/content-blocks/' . $image->image_path);
                        })->values()
                    ];
                });
                
                return $project;
            });
            
            return response()->json([
                'status' => 'success',
                'message' => 'All projects with images retrieved successfully',
                'data' => $projects
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve projects',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get a single project with comprehensive details by ID
     */
    public function getProjectWithDetailsById(Request $request, $id = null): JsonResponse
    {
        try {
            // If no ID provided, return list of available project IDs
            if ($id === null) {
                $availableProjects = Project::select('id', 'prj_title_en', 'prj_title_ar')->get();
                
                return response()->json([
                    'status' => 'info',
                    'message' => 'No project ID provided. Here are available project IDs:',
                    'available_projects' => $availableProjects,
                    'total_projects' => $availableProjects->count(),
                    'usage' => 'Use: GET /api/projects/with-details-using-id/{id} where {id} is one of the project IDs above'
                ], 200);
            }
            
            // Check if ID is valid (numeric and exists)
            if (!is_numeric($id) || $id <= 0) {
                $availableProjects = Project::select('id', 'prj_title_en', 'prj_title_ar')->get();
                
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid project ID provided. ID must be a positive number.',
                    'available_projects' => $availableProjects,
                    'total_projects' => $availableProjects->count(),
                    'usage' => 'Use: GET /api/projects/with-details-using-id/{id} where {id} is one of the project IDs above'
                ], 400);
            }
            
            $project = Project::find($id);
            
            // If project not found, return list of available IDs
            if (!$project) {
                $availableProjects = Project::select('id', 'prj_title_en', 'prj_title_ar')->get();
                
                return response()->json([
                    'status' => 'error',
                    'message' => 'Project with ID ' . $id . ' not found.',
                    'available_projects' => $availableProjects,
                    'total_projects' => $availableProjects->count(),
                    'usage' => 'Use: GET /api/projects/with-details-using-id/{id} where {id} is one of the project IDs above'
                ], 404);
            }
            
            // Add area information with only name_en and name_ar (before images)
            if ($project->prj_areaId) {
                $area = \App\Models\Area::find($project->prj_areaId);
                if ($area) {
                    $project->area = [
                        'name_en' => $area->name_en,
                        'name_ar' => $area->name_ar
                    ];
                }
            }
            
            // Add real estate company information with only company_name_ar and company_name_en
            if ($project->company_id) {
                $company = \App\Models\RealEstateCompany::find($project->company_id);
                if ($company) {
                    $project->company = [
                        'company_name_ar' => $company->company_name_ar,
                        'company_name_en' => $company->company_name_en
                    ];
                } else {
                    $project->company = [
                        'company_name_ar' => null,
                        'company_name_en' => null
                    ];
                }
            } else {
                $project->company = [
                    'company_name_ar' => null,
                    'company_name_en' => null
                ];
            }
            
            // Load project images for this specific project
            $projectImages = \App\Models\ProjectImage::where('project_id', $project->id)->get();
            
            // Organize images by type - only image_path URLs
            $project->images = [
                'interior' => $projectImages->where('type', 'interior')->pluck('image_path')->map(function ($path) {
                    return asset('projects/images/' . $path);
                })->values(),
                'exterior' => $projectImages->where('type', 'exterior')->pluck('image_path')->map(function ($path) {
                    return asset('projects/images/' . $path);
                })->values(),
                'floorplan' => $projectImages->where('type', 'floorplan')->pluck('image_path')->map(function ($path) {
                    return asset('projects/images/' . $path);
                })->values()
            ];
            
            // Load project details for this specific project
            $projectDetails = \App\Models\ProjectDetail::where('project_id', $project->id)->orderBy('order')->get();
            
            // Add project details object
            $project->ProjectDetails = $projectDetails->map(function ($detail) {
                return [
                    'detail_ar' => $detail->detail_ar,
                    'detail_en' => $detail->detail_en,
                    'detail_value_ar' => $detail->detail_value_ar,
                    'detail_value_en' => $detail->detail_value_en
                ];
            });
            
            // Load project amenities for this specific project
            $projectAmenities = \App\Models\ProjectAmenity::where('project_id', $project->id)->get();
            
            // Add amenities object
            $project->Amenities = $projectAmenities->map(function ($amenity) {
                return [
                    'amenity_type' => $amenity->amenity_type,
                    'is_active' => $amenity->is_active
                ];
            });
            
            // Load project content blocks with their images for this specific project
            $projectContentBlocks = \App\Models\ProjectContentBlock::with('images')->where('project_id', $project->id)->orderBy('order')->get();
            
            // Add project content blocks object
            $project->project_content_blocks = $projectContentBlocks->map(function ($block) {
                return [
                    'order' => $block->order,
                    'title_ar' => $block->title_ar,
                    'title_en' => $block->title_en,
                    'content_ar' => $block->content_ar,
                    'content_en' => $block->content_en,
                    'images' => $block->images->map(function ($image) {
                        return asset('projects/content-blocks/' . $image->image_path);
                    })->values()
                ];
            });
            
            return response()->json([
                'status' => 'success',
                'message' => 'Project with details retrieved successfully',
                'data' => $project
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve project',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Search projects with comprehensive details (same structure as getProjectWithDetailsById)
     */
    public function searchProjectsWithDetails(Request $request): JsonResponse
    {
        try {
            $query = Project::query();
            
            // Search by title (Arabic and English)
            if ($request->has('title') && $request->title) {
                $searchTerm = $request->title;
                $query->where(function($q) use ($searchTerm) {
                    $q->where('prj_title_ar', 'LIKE', "%{$searchTerm}%")
                      ->orWhere('prj_title_en', 'LIKE', "%{$searchTerm}%");
                });
            }
            
            // Search by description (Arabic and English)
            if ($request->has('description') && $request->description) {
                $searchTerm = $request->description;
                $query->where(function($q) use ($searchTerm) {
                    $q->where('prj_description_ar', 'LIKE', "%{$searchTerm}%")
                      ->orWhere('prj_description_en', 'LIKE', "%{$searchTerm}%");
                });
            }
            
            // Search by project number
            if ($request->has('project_number') && $request->project_number) {
                $query->where('prj_projectNumber', 'LIKE', "%{$request->project_number}%");
            }
            
            // Search by ADM
            if ($request->has('adm') && $request->adm) {
                $query->where('prj_adm', 'LIKE', "%{$request->adm}%");
            }
            
            // Search by CN
            if ($request->has('cn') && $request->cn) {
                $query->where('prj_cn', 'LIKE', "%{$request->cn}%");
            }
            
            // Search by Madhmoun Permit Number
            if ($request->has('madhmoun_permit') && $request->madhmoun_permit) {
                $query->where('prj_MadhmounPermitNumber', 'LIKE', "%{$request->madhmoun_permit}%");
            }
            
            // Filter by area
            if ($request->has('area_id') && $request->area_id) {
                $query->where('prj_areaId', $request->area_id);
            }
            
            // Filter by company
            if ($request->has('company_id') && $request->company_id) {
                $query->where('company_id', $request->company_id);
            }
            
            // Get projects
            $projects = $query->get();
            
            // Transform each project to add organized images and project details (same as getProjectWithDetailsById)
            $projects->transform(function ($project) {
                // Add area information with only name_en and name_ar (before images)
                if ($project->prj_areaId) {
                    $area = \App\Models\Area::find($project->prj_areaId);
                    if ($area) {
                        $project->area = [
                            'name_en' => $area->name_en,
                            'name_ar' => $area->name_ar
                        ];
                    }
                }
                
                // Add real estate company information with only company_name_ar and company_name_en
                if ($project->company_id) {
                    $company = \App\Models\RealEstateCompany::find($project->company_id);
                    if ($company) {
                        $project->company = [
                            'company_name_ar' => $company->company_name_ar,
                            'company_name_en' => $company->company_name_en
                        ];
                    } else {
                        $project->company = [
                            'company_name_ar' => null,
                            'company_name_en' => null
                        ];
                    }
                } else {
                    $project->company = [
                        'company_name_ar' => null,
                        'company_name_en' => null
                    ];
                }
                
                // Load project images for this specific project
                $projectImages = \App\Models\ProjectImage::where('project_id', $project->id)->get();
                
                // Organize images by type - only image_path URLs
                $project->images = [
                    'interior' => $projectImages->where('type', 'interior')->pluck('image_path')->map(function ($path) {
                        return asset('projects/images/' . $path);
                    })->values(),
                    'exterior' => $projectImages->where('type', 'exterior')->pluck('image_path')->map(function ($path) {
                        return asset('projects/images/' . $path);
                    })->values(),
                    'floorplan' => $projectImages->where('type', 'floorplan')->pluck('image_path')->map(function ($path) {
                        return asset('projects/images/' . $path);
                    })->values()
                ];
                
                // Load project details for this specific project
                $projectDetails = \App\Models\ProjectDetail::where('project_id', $project->id)->orderBy('order')->get();
                
                // Add project details object
                $project->ProjectDetails = $projectDetails->map(function ($detail) {
                    return [
                        'detail_ar' => $detail->detail_ar,
                        'detail_en' => $detail->detail_en,
                        'detail_value_ar' => $detail->detail_value_ar,
                        'detail_value_en' => $detail->detail_value_en
                    ];
                });
                
                // Load project amenities for this specific project
                $projectAmenities = \App\Models\ProjectAmenity::where('project_id', $project->id)->get();
                
                // Add amenities object
                $project->Amenities = $projectAmenities->map(function ($amenity) {
                    return [
                        'amenity_type' => $amenity->amenity_type,
                        'is_active' => $amenity->is_active
                    ];
                });
                
                // Load project content blocks with their images for this specific project
                $projectContentBlocks = \App\Models\ProjectContentBlock::with('images')->where('project_id', $project->id)->orderBy('order')->get();
                
                // Add project content blocks object
                $project->project_content_blocks = $projectContentBlocks->map(function ($block) {
                    return [
                        'order' => $block->order,
                        'title_ar' => $block->title_ar,
                        'title_en' => $block->title_en,
                        'content_ar' => $block->content_ar,
                        'content_en' => $block->content_en,
                        'images' => $block->images->map(function ($image) {
                            return asset('projects/content-blocks/' . $image->image_path);
                        })->values()
                    ];
                });
                
                return $project;
            });
            
            return response()->json([
                'status' => 'success',
                'message' => 'Projects search completed successfully',
                'total_results' => $projects->count(),
                'search_criteria' => $request->all(),
                'data' => $projects
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to search projects',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create a project with all its components in one endpoint
     */
    public function createProjectWithAllDetails(Request $request): JsonResponse
    {
        try {
            // Normalize boolean-like inputs before validation (e.g., "true"/"false" to 1/0)
            if ($request->has('project_amenities') && is_array($request->project_amenities)) {
                $normalizedAmenities = [];
                foreach ($request->project_amenities as $amenity) {
                    if (is_array($amenity) && array_key_exists('is_active', $amenity)) {
                        $val = $amenity['is_active'];
                        // Convert common truthy/falsey strings to boolean 1/0
                        $bool = filter_var($val, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
                        if ($bool === null) {
                            // Leave as-is to let validation catch incorrect values
                            $amenity['is_active'] = $val;
                        } else {
                            $amenity['is_active'] = $bool ? 1 : 0;
                        }
                    }
                    $normalizedAmenities[] = $amenity;
                }
                $request->merge(['project_amenities' => $normalizedAmenities]);
            }

            // Validate main project data
            $request->validate([
                'prj_title_ar' => 'required|string|max:255',
                'prj_title_en' => 'required|string|max:255',
                'prj_description_ar' => 'nullable|string',
                'prj_description_en' => 'nullable|string',
                'prj_areaId' => 'required|exists:areas,id',
                'company_id' => 'nullable|exists:real_estate_companies,id',
                'prj_adm' => 'nullable|string|max:255',
                'prj_cn' => 'nullable|string|max:255',
                'prj_projectNumber' => 'nullable|string|max:255',
                'prj_MadhmounPermitNumber' => 'nullable|string|max:255',
                'prj_brochurefile' => 'nullable|file|mimes:pdf',
                'prj_floorplan' => 'nullable|file|mimes:pdf',
                
                // Project Details validation
                'project_details' => 'nullable|array',
                'project_details.*.detail_ar' => 'required_with:project_details|string|max:255',
                'project_details.*.detail_en' => 'required_with:project_details|string|max:255',
                'project_details.*.detail_value_ar' => 'required_with:project_details|string|max:255',
                'project_details.*.detail_value_en' => 'required_with:project_details|string|max:255',
                'project_details.*.order' => 'required_with:project_details|integer|min:0',
                
                // Project Amenities validation
                'project_amenities' => 'nullable|array',
                'project_amenities.*.amenity_type' => 'required_with:project_amenities|string|in:infinity_pool,concierge_services,retail_fnb,bbq_area,cinema_game_room,gym,wellness_facilities,splash_pad,sauna_wellness,multipurpose_court',
                'project_amenities.*.is_active' => 'required_with:project_amenities|boolean',
                
                // Project Content Blocks validation
                'project_content_blocks' => 'nullable|array',
                'project_content_blocks.*.title_ar' => 'required_with:project_content_blocks|string|max:255',
                'project_content_blocks.*.title_en' => 'required_with:project_content_blocks|string|max:255',
                'project_content_blocks.*.content_ar' => 'required_with:project_content_blocks|string',
                'project_content_blocks.*.content_en' => 'required_with:project_content_blocks|string',
                'project_content_blocks.*.order' => 'required_with:project_content_blocks|integer|min:0',
                'project_content_blocks.*.images' => 'nullable|array',
                'project_content_blocks.*.images.*' => 'nullable|file|mimes:jpg,jpeg,png,gif',
                
                // Project Images validation
                'project_images' => 'nullable|array',
                'project_images.interior' => 'nullable|array',
                'project_images.interior.*' => 'nullable|file|mimes:jpg,jpeg,png,gif',
                'project_images.exterior' => 'nullable|array',
                'project_images.exterior.*' => 'nullable|file|mimes:jpg,jpeg,png,gif',
                'project_images.floorplan' => 'nullable|array',
                'project_images.floorplan.*' => 'nullable|file|mimes:jpg,jpeg,png,gif',
            ]);

            // Create the main project
            $project = new Project();
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

            // Handle brochure file upload
            if ($request->hasFile('prj_brochurefile')) {
                $brochureFileName = time() . '_brochure.' . $request->prj_brochurefile->extension();
                $request->prj_brochurefile->move(public_path('projects/brochures'), $brochureFileName);
                $project->prj_brochurefile = $brochureFileName;
            }

            // Handle floorplan file upload
            if ($request->hasFile('prj_floorplan')) {
                $floorplanFileName = time() . '_floorplan.' . $request->prj_floorplan->extension();
                $request->prj_floorplan->move(public_path('projects/floorplans'), $floorplanFileName);
                $project->prj_floorplan = $floorplanFileName;
            }

            $project->save();

            // Create Project Details
            if ($request->has('project_details') && is_array($request->project_details)) {
                foreach ($request->project_details as $detailData) {
                    \App\Models\ProjectDetail::create([
                        'project_id' => $project->id,
                        'detail_ar' => $detailData['detail_ar'],
                        'detail_en' => $detailData['detail_en'],
                        'detail_value_ar' => $detailData['detail_value_ar'],
                        'detail_value_en' => $detailData['detail_value_en'],
                        'order' => $detailData['order']
                    ]);
                }
            }

            // Create Project Amenities (use provided or auto-fill all default types)
            $amenityTypes = [
                'infinity_pool',
                'concierge_services',
                'retail_fnb',
                'bbq_area',
                'cinema_game_room',
                'gym',
                'wellness_facilities',
                'splash_pad',
                'sauna_wellness',
                'multipurpose_court'
            ];
            if ($request->has('project_amenities') && is_array($request->project_amenities) && count($request->project_amenities) > 0) {
                foreach ($request->project_amenities as $amenityData) {
                    \App\Models\ProjectAmenity::create([
                        'project_id' => $project->id,
                        'amenity_type' => $amenityData['amenity_type'],
                        'is_active' => (int) !!$amenityData['is_active']
                    ]);
                }
            } else {
                // Auto-create all amenities defaulting to inactive (0)
                foreach ($amenityTypes as $type) {
                    \App\Models\ProjectAmenity::create([
                        'project_id' => $project->id,
                        'amenity_type' => $type,
                        'is_active' => 0
                    ]);
                }
            }

            // Create Project Content Blocks with Images
            if ($request->has('project_content_blocks') && is_array($request->project_content_blocks)) {
                foreach ($request->project_content_blocks as $blockData) {
                    $contentBlock = \App\Models\ProjectContentBlock::create([
                        'project_id' => $project->id,
                        'title_ar' => $blockData['title_ar'],
                        'title_en' => $blockData['title_en'],
                        'content_ar' => $blockData['content_ar'],
                        'content_en' => $blockData['content_en'],
                        'order' => $blockData['order']
                    ]);

                    // Handle content block images
                    if (isset($blockData['images']) && is_array($blockData['images'])) {
                        foreach ($blockData['images'] as $image) {
                            if ($image && $image->isValid()) {
                                $imageFileName = time() . '_' . uniqid() . '.' . $image->extension();
                                $image->move(public_path('projects/content-blocks'), $imageFileName);
                                
                                \App\Models\ProjectContentBlockImage::create([
                                    'content_block_id' => $contentBlock->id,
                                    'image_path' => $imageFileName
                                ]);
                            }
                        }
                    }
                }
            }

            // Create Project Images
            if ($request->has('project_images')) {
                $projectImages = $request->project_images;
                
                // Handle Interior Images
                if (isset($projectImages['interior']) && is_array($projectImages['interior'])) {
                    foreach ($projectImages['interior'] as $image) {
                        if ($image && $image->isValid()) {
                            $imageFileName = time() . '_' . uniqid() . '_interior.' . $image->extension();
                            $image->move(public_path('projects/images'), $imageFileName);
                            
                            \App\Models\ProjectImage::create([
                                'project_id' => $project->id,
                                'image_path' => $imageFileName,
                                'type' => 'interior'
                            ]);
                        }
                    }
                }

                // Handle Exterior Images
                if (isset($projectImages['exterior']) && is_array($projectImages['exterior'])) {
                    foreach ($projectImages['exterior'] as $image) {
                        if ($image && $image->isValid()) {
                            $imageFileName = time() . '_' . uniqid() . '_exterior.' . $image->extension();
                            $image->move(public_path('projects/images'), $imageFileName);
                            
                            \App\Models\ProjectImage::create([
                                'project_id' => $project->id,
                                'image_path' => $imageFileName,
                                'type' => 'exterior'
                            ]);
                        }
                    }
                }

                // Handle Floorplan Images
                if (isset($projectImages['floorplan']) && is_array($projectImages['floorplan'])) {
                    foreach ($projectImages['floorplan'] as $image) {
                        if ($image && $image->isValid()) {
                            $imageFileName = time() . '_' . uniqid() . '_floorplan.' . $image->extension();
                            $image->move(public_path('projects/images'), $imageFileName);
                            
                            \App\Models\ProjectImage::create([
                                'project_id' => $project->id,
                                'image_path' => $imageFileName,
                                'type' => 'floorplan'
                            ]);
                        }
                    }
                }
            }

            // Return the created project with all its details (same structure as getProjectWithDetailsById)
            $createdProject = Project::find($project->id);
            
            // Add area information
            if ($createdProject->prj_areaId) {
                $area = \App\Models\Area::find($createdProject->prj_areaId);
                if ($area) {
                    $createdProject->area = [
                        'name_en' => $area->name_en,
                        'name_ar' => $area->name_ar
                    ];
                }
            }
            
            // Add company information
            if ($createdProject->company_id) {
                $company = \App\Models\RealEstateCompany::find($createdProject->company_id);
                if ($company) {
                    $createdProject->company = [
                        'company_name_ar' => $company->company_name_ar,
                        'company_name_en' => $company->company_name_en
                    ];
                } else {
                    $createdProject->company = [
                        'company_name_ar' => null,
                        'company_name_en' => null
                    ];
                }
            } else {
                $createdProject->company = [
                    'company_name_ar' => null,
                    'company_name_en' => null
                ];
            }
            
            // Add images
            $projectImages = \App\Models\ProjectImage::where('project_id', $createdProject->id)->get();
            $createdProject->images = [
                'interior' => $projectImages->where('type', 'interior')->pluck('image_path')->map(function ($path) {
                    return asset('projects/images/' . $path);
                })->values(),
                'exterior' => $projectImages->where('type', 'exterior')->pluck('image_path')->map(function ($path) {
                    return asset('projects/images/' . $path);
                })->values(),
                'floorplan' => $projectImages->where('type', 'floorplan')->pluck('image_path')->map(function ($path) {
                    return asset('projects/images/' . $path);
                })->values()
            ];
            
            // Add project details
            $projectDetails = \App\Models\ProjectDetail::where('project_id', $createdProject->id)->orderBy('order')->get();
            $createdProject->ProjectDetails = $projectDetails->map(function ($detail) {
                return [
                    'detail_ar' => $detail->detail_ar,
                    'detail_en' => $detail->detail_en,
                    'detail_value_ar' => $detail->detail_value_ar,
                    'detail_value_en' => $detail->detail_value_en
                ];
            });
            
            // Add amenities
            $projectAmenities = \App\Models\ProjectAmenity::where('project_id', $createdProject->id)->get();
            $createdProject->Amenities = $projectAmenities->map(function ($amenity) {
                return [
                    'amenity_type' => $amenity->amenity_type,
                    'is_active' => $amenity->is_active
                ];
            });
            
            // Add content blocks with images
            $projectContentBlocks = \App\Models\ProjectContentBlock::with('images')->where('project_id', $createdProject->id)->orderBy('order')->get();
            $createdProject->project_content_blocks = $projectContentBlocks->map(function ($block) {
                return [
                    'order' => $block->order,
                    'title_ar' => $block->title_ar,
                    'title_en' => $block->title_en,
                    'content_ar' => $block->content_ar,
                    'content_en' => $block->content_en,
                    'images' => $block->images->map(function ($image) {
                        return asset('projects/content-blocks/' . $image->image_path);
                    })->values()
                ];
            });

            return response()->json([
                'status' => 'success',
                'message' => 'Project created successfully with all details',
                'data' => $createdProject
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create project',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
