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

        // Return simplified property data (only available fields)
        $simplifiedProperties = $properties->map(function ($property) {
            return [
                'id' => $property->propertyid,
                'price' => number_format($property->propertyprice, 0, '.', ','),
                'price_currency' => 'AED',
                'property_type' => ucfirst($property->propertypurpose),
                'rooms' => $property->propertyrooms,
                'bathrooms' => $property->propertybathrooms,
                'area' => number_format($property->propertyarea, 0, '.', ','),
                'area_unit' => 'sqft',
                'location' => $property->propertyloaction,
                'handover_date' => $property->propertyhandover ? $property->propertyhandover->format('M Y') : null,
                'features_display' => $property->propertyfeatures ? array_map(function($feature) {
                    return ucwords(str_replace('_', ' ', $feature));
                }, array_slice($property->propertyfeatures, 0, 3)) : [],
                'images' => $property->propertyimages ? array_map(function($image) {
                    return url('storage/' . $image);
                }, array_slice($property->propertyimages, 0, 5)) : [],
                'contact' => $property->employee ? [
                    'name' => app()->getLocale() === 'ar' ? $property->employee->name_ar : $property->employee->name_en,
                    'email' => $property->employee->email,
                    'phone' => $property->employee->phone,
                ] : null,
                'payment_plan' => $property->paymentPlan ? [
                    'name' => $property->paymentPlan->getLocalizedNameAttribute(),
                    'description' => $property->paymentPlan->getLocalizedDescriptionAttribute(),
                ] : null,
                'created_at' => $property->created_at->format('Y-m-d H:i:s'),
            ];
        });

        return response()->json([
            'data' => $simplifiedProperties,
            'links' => [
                'first' => $properties->url(1),
                'last' => $properties->url($properties->lastPage()),
                'prev' => $properties->previousPageUrl(),
                'next' => $properties->nextPageUrl(),
            ],
            'meta' => [
                'current_page' => $properties->currentPage(),
                'from' => $properties->firstItem(),
                'last_page' => $properties->lastPage(),
                'links' => $properties->getUrlRange(1, $properties->lastPage()),
                'path' => $properties->path(),
                'per_page' => $properties->perPage(),
                'to' => $properties->lastItem(),
                'total' => $properties->total(),
            ]
        ]);
    }

    /**
     * Get all properties with full details
     */
    public function getAllWithDetails(Request $request)
    {
        $query = Property::with([
            'project.area', 
            'project.company', 
            'paymentPlan', 
            'employee'
        ]);

        // Apply filters if provided
        if ($request->has('min_price') && $request->min_price !== null) {
            $query->where('propertyprice', '>=', $request->min_price);
        }

        if ($request->has('max_price') && $request->max_price !== null) {
            $query->where('propertyprice', '<=', $request->max_price);
        }

        if ($request->has('project_id') && $request->project_id !== null) {
            $query->where('propertyproject', $request->project_id);
        }

        if ($request->has('purpose') && $request->purpose !== null) {
            $query->where('propertypurpose', $request->purpose);
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

        $properties = $query->orderBy('created_at', 'desc')->get();

        // Return full property details
        $fullProperties = $properties->map(function ($property) {
            return [
                // Basic Property Info
                'id' => $property->propertyid,
                'unit_number' => 'Unit ' . $property->propertyid,
                'property_type' => ucfirst($property->propertypurpose),
                'unit_type' => 'Apartment', // Default value
                'status' => 'Available', // Default value
                'priority' => 'High', // Default value
                
                // Pricing Details
                'price' => number_format($property->propertyprice, 0, '.', ','),
                'price_currency' => 'AED',
                'price_per_sqft' => $property->propertyarea > 0 ? number_format($property->propertyprice / $property->propertyarea, 0, '.', ',') : '0',
                'discount_percentage' => '10%', // Default value
                'discounted_price' => number_format($property->propertyprice * 0.9, 0, '.', ','),
                'down_payment_amount' => number_format($property->propertyprice * 0.2, 0, '.', ','),
                'down_payment_percentage' => '20%',
                'remaining_amount' => number_format($property->propertyprice * 0.8, 0, '.', ','),
                
                // Property Specifications
                'rooms' => $property->propertyrooms,
                'bathrooms' => $property->propertybathrooms,
                'area' => number_format($property->propertyarea, 0, '.', ','),
                'area_unit' => 'sqft',
                'furnished_area' => number_format($property->propertyarea * 0.75, 0, '.', ','), // Estimated
                'unfurnished_area' => number_format($property->propertyarea * 0.25, 0, '.', ','), // Estimated
                'floor' => 'Floor ' . rand(1, 30), // Random floor for demo
                'view' => 'Sea View', // Default value
                'elevators' => 2, // Default value
                'parking_spaces' => 1, // Default value
                
                // Location Details
                'location' => $property->propertyloaction,
                'area_name' => $property->project && $property->project->area ? $property->project->area->name_en : $property->propertyloaction,
                'city' => 'Abu Dhabi', // Default value
                'emirate' => 'Abu Dhabi', // Default value
                'postal_code' => '12345', // Default value
                
                // Project Information
                'project' => $property->project ? [
                    'id' => $property->project->projectid,
                    'name' => app()->getLocale() === 'ar' ? $property->project->name_ar : $property->project->name_en,
                    'type' => 'Residential', // Default value
                    'status' => 'Under Construction', // Default value
                    'developer' => $property->project->company ? (app()->getLocale() === 'ar' ? $property->project->company->name_ar : $property->project->company->name_en) : 'Aura Home',
                    'images' => [], // Project images not available in current schema
                ] : null,
                
                // Features and Amenities
                'features_display' => $property->propertyfeatures ? array_map(function($feature) {
                    return ucwords(str_replace('_', ' ', $feature));
                }, $property->propertyfeatures) : [],
                'amenities' => ['Gym', 'Pool', 'Parking'], // Default amenities
                
                // Images
                'images' => $property->propertyimages ? array_map(function($image) {
                    return url('storage/' . $image);
                }, $property->propertyimages) : [],
                
                // Handover Information
                'handover_date' => $property->propertyhandover ? $property->propertyhandover->format('M Y') : null,
                'handover_status' => 'On Schedule', // Default value
                
                // Contact Information
                'contact' => $property->employee ? [
                    'name' => app()->getLocale() === 'ar' ? $property->employee->name_ar : $property->employee->name_en,
                    'email' => $property->employee->email,
                    'phone' => $property->employee->phone,
                    'position' => 'Project Development Manager', // Default value
                ] : null,
                
                // Payment Plan
                'payment_plan' => $property->paymentPlan ? [
                    'id' => $property->paymentPlan->paymentplanid,
                    'name' => app()->getLocale() === 'ar' ? $property->paymentPlan->name_ar : $property->paymentPlan->name_en,
                    'description' => app()->getLocale() === 'ar' ? $property->paymentPlan->description_ar : $property->paymentPlan->description_en,
                    'installments' => 12, // Default value
                    'down_payment' => '20%',
                    'remaining_payment' => 'Upon Handover',
                ] : null,
                
                // Timestamps
                'created_at' => $property->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $property->updated_at->format('Y-m-d H:i:s'),
                'published_date' => $property->created_at->format('Y-m-d'),
                'last_updated' => $property->updated_at->format('Y-m-d'),
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $fullProperties,
            'total' => $fullProperties->count(),
            'message' => 'Properties retrieved successfully with full details'
        ]);
    }

    /**
     * Get property with full details by ID
     */
    public function getAllWithDetailsbyid(Request $request, $id)
    {
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|exists:properties,propertyid'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $property = Property::with([
                'project.area', 
                'project.company', 
                'paymentPlan', 
                'employee'
            ])->where('propertyid', $id)->first();

            if (!$property) {
                return response()->json([
                    'success' => false,
                    'message' => 'Property not found'
                ], 404);
            }

            // Get property images from JSON field
            $images = [];
            if ($property->propertyimages && is_array($property->propertyimages)) {
                foreach ($property->propertyimages as $image) {
                    if ($image) {
                        $images[] = asset('properties/images/' . $image);
                    }
                }
            }

            // Calculate pricing details
            $price = $property->propertyprice;
            $discountPercentage = 10; // Default discount
            $discountedPrice = $price * (1 - $discountPercentage / 100);
            $downPaymentPercentage = 20; // Default down payment percentage
            $downPaymentAmount = $discountedPrice * ($downPaymentPercentage / 100);
            $remainingAmount = $discountedPrice - $downPaymentAmount;
            $pricePerSqft = $property->propertyarea > 0 ? $price / $property->propertyarea : 0;

            // Get property features
            $features = $property->propertyfeatures ?? [];
            $featuresDisplay = is_array($features) ? $features : [];

            // Build full property details
            $fullProperty = [
                'id' => $property->propertyid,
                'unit_number' => 'Unit ' . $property->propertyid,
                'property_type' => ucfirst($property->propertypurpose ?? 'Sale'),
                'unit_type' => 'Apartment', // Default value
                'status' => 'Available', // Default value
                'priority' => 'High', // Default value
                'price' => number_format($price, 0, '.', ','),
                'price_currency' => 'AED',
                'price_per_sqft' => number_format($pricePerSqft, 0, '.', ','),
                'discount_percentage' => $discountPercentage . '%',
                'discounted_price' => number_format($discountedPrice, 0, '.', ','),
                'down_payment_amount' => number_format($downPaymentAmount, 0, '.', ','),
                'down_payment_percentage' => $downPaymentPercentage . '%',
                'remaining_amount' => number_format($remainingAmount, 0, '.', ','),
                'rooms' => $property->propertyrooms,
                'bathrooms' => $property->propertybathrooms,
                'area' => number_format($property->propertyarea, 0, '.', ','),
                'area_unit' => 'sqft',
                'furnished_area' => number_format($property->propertyarea * 0.75, 0, '.', ','), // 75% furnished
                'unfurnished_area' => number_format($property->propertyarea * 0.25, 0, '.', ','), // 25% unfurnished
                'floor' => 'Floor ' . rand(1, 30), // Random floor for demo
                'view' => 'Sea View', // Default value
                'elevators' => 2, // Default value
                'parking_spaces' => 1, // Default value
                'location' => $property->propertyloaction ?? 'Al Reem Island',
                'area_name' => $property->project && $property->project->area ? 
                    (app()->getLocale() === 'ar' ? $property->project->area->area_name_ar : $property->project->area->area_name_en) : 
                    'Al Reem Island',
                'city' => 'Abu Dhabi', // Default value
                'emirate' => 'Abu Dhabi', // Default value
                'postal_code' => '12345', // Default value
                
                // Project Information
                'project' => [
                    'id' => $property->project ? $property->project->id : null,
                    'name' => $property->project ? (app()->getLocale() === 'ar' ? $property->project->prj_title_ar : $property->project->prj_title_en) : null,
                    'type' => 'Residential', // Default value
                    'status' => 'Under Construction', // Default value
                    'developer' => $property->project && $property->project->company ? 
                        (app()->getLocale() === 'ar' ? $property->project->company->company_name_ar : $property->project->company->company_name_en) : null,
                    'images' => [] // Default empty array
                ],
                
                'features_display' => $featuresDisplay,
                'amenities' => ['Gym', 'Pool', 'Parking'], // Default amenities
                'images' => $images,
                'handover_date' => $property->propertyhandover ? $property->propertyhandover->format('M Y') : 'Sep 2025',
                'handover_status' => 'On Schedule', // Default value
                
                // Contact Information
                'contact' => $property->employee ? [
                    'name' => (app()->getLocale() === 'ar' ? $property->employee->name_ar : $property->employee->name_en) . ' – Project Development Manager',
                    'email' => $property->employee->email,
                    'phone' => $property->employee->phone,
                    'position' => 'Project Development Manager',
                    'urlimage' => $property->employee->image ? url('storage/developers/' . $property->employee->image) : null,
                ] : [
                    'name' => 'Mohamed Ali – Project Development Manager',
                    'email' => 'mohamed.ali@aurahome.ae',
                    'phone' => '+971 50 123 4567',
                    'position' => 'Project Development Manager',
                    'urlimage' => null,
                ],
                
                // Payment Plan
                'payment_plan' => $property->paymentPlan ? [
                    'id' => $property->paymentPlan->paymentplanid,
                    'name' => app()->getLocale() === 'ar' ? $property->paymentPlan->name_ar : $property->paymentPlan->name_en,
                    'description' => app()->getLocale() === 'ar' ? $property->paymentPlan->description_ar : $property->paymentPlan->description_en,
                    'installments' => 12,
                    'down_payment' => '20%',
                    'remaining_payment' => 'Upon Handover',
                ] : [
                    'id' => null,
                    'name' => '12 Months Installments',
                    'description' => 'Pay the amount over 12 monthly installments',
                    'installments' => 12,
                    'down_payment' => '20%',
                    'remaining_payment' => 'Upon Handover',
                ],
                
                // Timestamps
                'created_at' => $property->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $property->updated_at->format('Y-m-d H:i:s'),
                'published_date' => $property->created_at->format('Y-m-d'),
                'last_updated' => $property->updated_at->format('Y-m-d'),
            ];

            return response()->json([
                'success' => true,
                'data' => $fullProperty,
                'message' => 'Property retrieved successfully with full details'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error loading property details',
                'error' => $e->getMessage()
            ], 500);
        }
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
            // Get project basic details first
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

            // Prepare project data
            $projectData = [
                'id' => $project->id,
                'title_ar' => $project->prj_title_ar,
                'title_en' => $project->prj_title_en,
                'description_ar' => $project->prj_description_ar,
                'description_en' => $project->prj_description_en,
                'image' => $mainImageUrl,
            ];

            // Get properties with filters
            $query = Property::with(['project.area', 'project.company', 'paymentPlan', 'employee'])
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

            // Return simplified property data (same format as /api/properties)
            $simplifiedProperties = $properties->map(function ($property) {
                return [
                    'id' => $property->propertyid,
                    'price' => number_format($property->propertyprice, 0, '.', ','),
                    'price_currency' => 'AED',
                    'property_type' => ucfirst($property->propertypurpose),
                    'rooms' => $property->propertyrooms,
                    'bathrooms' => $property->propertybathrooms,
                    'area' => number_format($property->propertyarea, 0, '.', ','),
                    'area_unit' => 'sqft',
                    'location' => $property->propertyloaction,
                    'handover_date' => $property->propertyhandover ? $property->propertyhandover->format('M Y') : null,
                    'features_display' => $property->propertyfeatures ? array_map(function($feature) {
                        return ucwords(str_replace('_', ' ', $feature));
                    }, array_slice($property->propertyfeatures, 0, 3)) : [],
                    'images' => $property->propertyimages ? array_map(function($image) {
                        return url('storage/' . $image);
                    }, array_slice($property->propertyimages, 0, 5)) : [],
                    'contact' => $property->employee ? [
                        'name' => app()->getLocale() === 'ar' ? $property->employee->name_ar : $property->employee->name_en,
                        'email' => $property->employee->email,
                        'phone' => $property->employee->phone,
                    ] : null,
                    'payment_plan' => $property->paymentPlan ? [
                        'name' => app()->getLocale() === 'ar' ? $property->paymentPlan->name_ar : $property->paymentPlan->name_en,
                        'description' => app()->getLocale() === 'ar' ? $property->paymentPlan->description_ar : $property->paymentPlan->description_en,
                    ] : null,
                    'created_at' => $property->created_at->format('Y-m-d H:i:s'),
                ];
            });

            return response()->json([
                'success' => true,
                'project' => $projectData,
                'data' => $simplifiedProperties,
                'links' => [
                    'first' => $properties->url(1),
                    'last' => $properties->url($properties->lastPage()),
                    'prev' => $properties->previousPageUrl(),
                    'next' => $properties->nextPageUrl(),
                ],
                'meta' => [
                    'current_page' => $properties->currentPage(),
                    'from' => $properties->firstItem(),
                    'last_page' => $properties->lastPage(),
                    'links' => $properties->getUrlRange(1, $properties->lastPage()),
                    'path' => $properties->path(),
                    'per_page' => $properties->perPage(),
                    'to' => $properties->lastItem(),
                    'total' => $properties->total(),
                ],
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
