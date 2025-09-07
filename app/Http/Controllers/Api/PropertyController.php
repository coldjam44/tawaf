<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PropertyResource;
use App\Models\Property;
use App\Models\Project;
use App\Models\Developer;
use App\Models\PaymentPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PropertyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Property::with(['project.area', 'project.company', 'paymentPlan', 'employee']);

        // Price filter
        if ($request->has('min_price') && $request->min_price !== null) {
            $query->where('propertyprice', '>=', $request->min_price);
        }

        if ($request->has('max_price') && $request->max_price !== null) {
            $query->where('propertyprice', '<=', $request->max_price);
        }

        // Project filter
        if ($request->has('project_id') && $request->project_id !== null) {
            $query->where('propertyproject', $request->project_id);
        }

        // Purpose filter
        if ($request->has('purpose') && $request->purpose !== null) {
            $query->where('propertypurpose', $request->purpose);
        }

        // Area filter
        if ($request->has('min_area') && $request->min_area !== null) {
            $query->where('propertyarea', '>=', $request->min_area);
        }

        if ($request->has('max_area') && $request->max_area !== null) {
            $query->where('propertyarea', '<=', $request->max_area);
        }

        // Rooms filter
        if ($request->has('rooms') && $request->rooms !== null) {
            $query->where('propertyrooms', $request->rooms);
        }

        // Bathrooms filter
        if ($request->has('bathrooms') && $request->bathrooms !== null) {
            $query->where('propertybathrooms', $request->bathrooms);
        }

        $properties = $query->orderBy('created_at', 'desc')->paginate(10);

        return PropertyResource::collection($properties);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'propertyproject' => 'required|exists:projects,id',
            'propertypurpose' => 'required|in:sale,rental,both,investment,vacation',
            'propertyprice' => 'required|numeric|min:0',
            'propertyrooms' => 'required|integer|min:0',
            'propertybathrooms' => 'required|integer|min:0',
            'propertyarea' => 'required|numeric|min:0',
            'propertyquantity' => 'required|integer|min:1',
            'propertyloaction' => 'required|string|max:255',
            'propertypaymentplan' => 'required|exists:payment_plans,id',
            'employee_id' => 'required|exists:developers,id',
            'propertyhandover' => 'nullable|date',
            'propertyfeatures' => 'nullable|array',
            'propertyfulldetils_ar' => 'nullable|string',
            'propertyfulldetils_en' => 'nullable|string',
            'propertyimages.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $data = $request->all();
            
            // Handle image uploads
            if ($request->hasFile('propertyimages')) {
                $images = [];
                foreach ($request->file('propertyimages') as $image) {
                    $path = $image->store('properties', 'public');
                    $images[] = $path;
                }
                $data['propertyimages'] = $images;
            }

            $property = Property::create($data);

            return response()->json([
                'success' => true,
                'message' => 'Property created successfully',
                'data' => new PropertyResource($property->load(['project.area', 'project.company', 'paymentPlan', 'employee']))
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating property',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Property $property)
    {
        $property->load(['project.area', 'project.company', 'paymentPlan', 'employee']);
        
        return response()->json([
            'success' => true,
            'data' => new PropertyResource($property)
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Property $property)
    {
        $validator = Validator::make($request->all(), [
            'propertyproject' => 'sometimes|required|exists:projects,id',
            'propertypurpose' => 'sometimes|required|in:sale,rental,both,investment,vacation',
            'propertyprice' => 'sometimes|required|numeric|min:0',
            'propertyrooms' => 'sometimes|required|integer|min:0',
            'propertybathrooms' => 'sometimes|required|integer|min:0',
            'propertyarea' => 'sometimes|required|numeric|min:0',
            'propertyquantity' => 'sometimes|required|integer|min:1',
            'propertyloaction' => 'sometimes|required|string|max:255',
            'propertypaymentplan' => 'sometimes|required|exists:payment_plans,id',
            'employee_id' => 'sometimes|required|exists:developers,id',
            'propertyhandover' => 'nullable|date',
            'propertyfeatures' => 'nullable|array',
            'propertyfulldetils_ar' => 'nullable|string',
            'propertyfulldetils_en' => 'nullable|string',
            'propertyimages.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $data = $request->all();
            
            // Handle image uploads
            if ($request->hasFile('propertyimages')) {
                $images = [];
                foreach ($request->file('propertyimages') as $image) {
                    $path = $image->store('properties', 'public');
                    $images[] = $path;
                }
                $data['propertyimages'] = $images;
            }

            $property->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Property updated successfully',
                'data' => new PropertyResource($property->load(['project.area', 'project.company', 'paymentPlan', 'employee']))
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating property',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Property $property)
    {
        try {
            // Delete associated images
            if ($property->propertyimages) {
                foreach ($property->propertyimages as $image) {
                    Storage::disk('public')->delete($image);
                }
            }

            $property->delete();

            return response()->json([
                'success' => true,
                'message' => 'Property deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting property',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get employees for a specific project
     */
    public function getProjectEmployees(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'project_id' => 'required|exists:projects,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $project = Project::with('company.developers')->find($request->project_id);
            
            if (!$project || !$project->company) {
                return response()->json([
                    'success' => true,
                    'data' => ['employees' => []]
                ]);
            }

            $employees = $project->company->developers->map(function($employee) {
                return [
                    'id' => $employee->id,
                    'name' => app()->getLocale() === 'ar' ? $employee->name_ar : $employee->name_en,
                    'email' => $employee->email,
                    'phone' => $employee->phone,
                    'company_name' => app()->getLocale() === 'ar' ? $employee->company->company_name_ar : $employee->company->company_name_en,
                ];
            });

            return response()->json([
                'success' => true,
                'data' => ['employees' => $employees]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error loading employees',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get projects for property creation
     */
    public function getProjects()
    {
        try {
            $projects = Project::with('area')->orderBy('id', 'desc')->get();
            
            $formattedProjects = $projects->map(function($project) {
                return [
                    'id' => $project->id,
                    'title' => $project->getTitle(),
                    'area' => $project->area ? (app()->getLocale() === 'ar' ? $project->area->name_ar : $project->area->name_en) : null,
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $formattedProjects
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error loading projects',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get payment plans
     */
    public function getPaymentPlans()
    {
        try {
            $paymentPlans = PaymentPlan::active()->orderBy('name_ar')->get();
            
            $formattedPlans = $paymentPlans->map(function($plan) {
                return [
                    'id' => $plan->id,
                    'name' => $plan->getLocalizedNameAttribute(),
                    'description' => $plan->getLocalizedDescriptionAttribute(),
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $formattedPlans
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error loading payment plans',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Advanced property search with filters
     */
    public function search(Request $request)
    {
        $query = Property::with(['project.area', 'project.company', 'paymentPlan', 'employee']);

        // Price range filter
        if ($request->has('min_price') && $request->min_price !== null) {
            $query->where('propertyprice', '>=', $request->min_price);
        }

        if ($request->has('max_price') && $request->max_price !== null) {
            $query->where('propertyprice', '<=', $request->max_price);
        }

        // Project filter
        if ($request->has('project_id') && $request->project_id !== null) {
            $query->where('propertyproject', $request->project_id);
        }

        // Purpose filter
        if ($request->has('purpose') && $request->purpose !== null) {
            $query->where('propertypurpose', $request->purpose);
        }

        // Area range filter
        if ($request->has('min_area') && $request->min_area !== null) {
            $query->where('propertyarea', '>=', $request->min_area);
        }

        if ($request->has('max_area') && $request->max_area !== null) {
            $query->where('propertyarea', '<=', $request->max_area);
        }

        // Rooms filter
        if ($request->has('rooms') && $request->rooms !== null) {
            $query->where('propertyrooms', $request->rooms);
        }

        // Bathrooms filter
        if ($request->has('bathrooms') && $request->bathrooms !== null) {
            $query->where('propertybathrooms', $request->bathrooms);
        }

        // Features filter
        if ($request->has('features') && is_array($request->features)) {
            foreach ($request->features as $feature) {
                $query->whereJsonContains('propertyfeatures', $feature);
            }
        }

        // Location filter
        if ($request->has('location') && $request->location !== null) {
            $query->where('propertyloaction', 'like', '%' . $request->location . '%');
        }

        // Sort by
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        
        if (in_array($sortBy, ['price', 'area', 'rooms', 'created_at'])) {
            $field = $sortBy === 'price' ? 'propertyprice' : 
                    ($sortBy === 'area' ? 'propertyarea' : 
                    ($sortBy === 'rooms' ? 'propertyrooms' : 'created_at'));
            $query->orderBy($field, $sortOrder);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $properties = $query->paginate($request->get('per_page', 10));

        return response()->json([
            'success' => true,
            'data' => PropertyResource::collection($properties),
            'filters_applied' => $request->only([
                'min_price', 'max_price', 'project_id', 'purpose', 
                'min_area', 'max_area', 'rooms', 'bathrooms', 
                'features', 'location', 'sort_by', 'sort_order'
            ])
        ]);
    }

    /**
     * Get properties by project ID
     */
    public function getPropertiesByProject(Request $request, $project_id)
    {
        $validator = Validator::make(['project_id' => $project_id], [
            'project_id' => 'required|exists:projects,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $query = Property::with(['project.area', 'project.company', 'project.projectImages' => function($query) {
                $query->where('is_featured', true)->orderBy('order', 'asc');
            }, 'paymentPlan', 'employee'])
                ->where('propertyproject', $project_id);

            // Apply filters if provided
            if ($request->has('purpose') && $request->purpose !== null) {
                $query->where('propertypurpose', $request->purpose);
            }

            if ($request->has('min_price') && $request->min_price !== null) {
                $query->where('propertyprice', '>=', $request->min_price);
            }

            if ($request->has('max_price') && $request->max_price !== null) {
                $query->where('propertyprice', '<=', $request->max_price);
            }

            if ($request->has('min_area') && $request->min_area !== null) {
                $query->where('propertyarea', '>=', $request->min_area);
            }

            if ($request->has('max_area') && $request->max_area !== null) {
                $query->where('propertyarea', '<=', $request->max_area);
            }

            if ($request->has('rooms') && $request->rooms !== null) {
                $query->where('propertyrooms', $request->rooms);
            }

            if ($request->has('bathrooms') && $request->bathrooms !== null) {
                $query->where('propertybathrooms', $request->bathrooms);
            }

            // Sort options
            $sortBy = $request->get('sort_by', 'created_at');
            $sortOrder = $request->get('sort_order', 'desc');
            
            if (in_array($sortBy, ['price', 'area', 'rooms', 'created_at'])) {
                $field = $sortBy === 'price' ? 'propertyprice' : 
                        ($sortBy === 'area' ? 'propertyarea' : 
                        ($sortBy === 'rooms' ? 'propertyrooms' : 'created_at'));
                $query->orderBy($field, $sortOrder);
            } else {
                $query->orderBy('created_at', 'desc');
            }

            $properties = $query->paginate($request->get('per_page', 10));

            return response()->json([
                'success' => true,
                'data' => PropertyResource::collection($properties),
                'project_id' => $project_id,
                'total_properties' => $properties->total(),
                'filters_applied' => $request->only([
                    'purpose', 'min_price', 'max_price', 'min_area', 'max_area', 
                    'rooms', 'bathrooms', 'sort_by', 'sort_order'
                ])
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error loading properties',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get project basic details
     */
    public function getProjectBasicDetails(Request $request, $project_id)
    {
        $validator = Validator::make(['project_id' => $project_id], [
            'project_id' => 'required|exists:projects,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $project = Project::with(['projectImages' => function($query) {
                $query->where('is_featured', true)->orderBy('order', 'asc');
            }])->find($project_id);

            if (!$project) {
                return response()->json([
                    'success' => false,
                    'message' => 'Project not found'
                ], 404);
            }

            // Get main/featured image
            $mainImage = $project->projectImages->first();
            $mainImageUrl = $mainImage ? asset('projects/images/' . $mainImage->image_path) : null;

            $projectData = [
                'id' => $project->id,
                'title_ar' => $project->prj_title_ar,
                'title_en' => $project->prj_title_en,
                'description_ar' => $project->prj_description_ar,
                'description_en' => $project->prj_description_en,
                'main_image' => $mainImageUrl,
            ];

            return response()->json([
                'success' => true,
                'data' => $projectData
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error loading project details',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all available projects basic details
     */
    public function getAllProjectsBasicDetails(Request $request)
    {
        try {
            $projects = Project::with(['projectImages' => function($query) {
                $query->where('is_featured', true)->orderBy('order', 'asc');
            }])->orderBy('id', 'desc')->get();

            $projectsData = $projects->map(function($project) {
                // Get main/featured image
                $mainImage = $project->projectImages->first();
                $mainImageUrl = $mainImage ? asset('projects/images/' . $mainImage->image_path) : null;

                return [
                    'id' => $project->id,
                    'title_ar' => $project->prj_title_ar,
                    'title_en' => $project->prj_title_en,
                    'description_ar' => $project->prj_description_ar,
                    'description_en' => $project->prj_description_en,
                    'main_image' => $mainImageUrl,
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $projectsData,
                'total_projects' => $projects->count()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error loading projects',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
