<?php

namespace App\Http\Controllers\Apis;

use App\Http\Controllers\Controller;
use App\Models\Area;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AreaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        try {
            $areas = Area::orderBy('name_' . app()->getLocale())->get();
            
            // Transform main_image to full URL
            $areas->each(function ($area) {
                if ($area->main_image) {
                    // Check if main image file exists
                    $mainImagePath = public_path('areas/images/' . $area->main_image);
                    if (file_exists($mainImagePath)) {
                        $area->main_image = asset('areas/images/' . $area->main_image);
                    } else {
                        $area->main_image = null; // Set to null if file doesn't exist
                    }
                }
            });
            
            return response()->json([
                'status' => 'success',
                'message' => 'Areas retrieved successfully',
                'data' => $areas
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve areas',
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
                'name_ar' => 'required|string|max:255',
                'name_en' => 'required|string|max:255',
                'slug' => 'required|string|max:255|unique:areas,slug',
                'main_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'about_community_overview_ar' => 'nullable|string',
                'about_community_overview_en' => 'nullable|string',
                'rental_sales_trends_ar' => 'nullable|string',
                'rental_sales_trends_en' => 'nullable|string',
                'roi_ar' => 'nullable|string',
                'roi_en' => 'nullable|string',
                'things_to_do_perks_ar' => 'nullable|string',
                'things_to_do_perks_en' => 'nullable|string'
            ]);

            $data = $request->except('main_image');
            
            // Handle image upload
            if ($request->hasFile('main_image')) {
                $image = $request->file('main_image');
                $imageName = time() . '_' . $image->getClientOriginalName();
                $image->move(public_path('areas/images'), $imageName);
                $data['main_image'] = $imageName;
            }

            $area = Area::create($data);

            // Transform main_image to full URL for response
            if ($area->main_image) {
                $area->main_image = asset('areas/images/' . $area->main_image);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Area created successfully',
                'data' => $area
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create area',
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
            $area = Area::findOrFail($id);
            
            // Transform main_image to full URL
            if ($area->main_image) {
                // Check if main image file exists
                $mainImagePath = public_path('areas/images/' . $area->main_image);
                if (file_exists($mainImagePath)) {
                    $area->main_image = asset('areas/images/' . $area->main_image);
                } else {
                    $area->main_image = null; // Set to null if file doesn't exist
                }
            }
            
            return response()->json([
                'status' => 'success',
                'message' => 'Area retrieved successfully',
                'data' => $area
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Area not found',
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
            $area = Area::findOrFail($id);
            
            $request->validate([
                'name_ar' => 'sometimes|required|string|max:255',
                'name_en' => 'sometimes|required|string|max:255',
                'slug' => 'sometimes|required|string|max:255|unique:areas,slug,' . $id,
                'main_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'about_community_overview_ar' => 'nullable|string',
                'about_community_overview_en' => 'nullable|string',
                'rental_sales_trends_ar' => 'nullable|string',
                'rental_sales_trends_en' => 'nullable|string',
                'roi_ar' => 'nullable|string',
                'roi_en' => 'nullable|string',
                'things_to_do_perks_ar' => 'nullable|string',
                'things_to_do_perks_en' => 'nullable|string'
            ]);

            $data = $request->except('main_image');
            
            // Handle image upload
            if ($request->hasFile('main_image')) {
                // Delete old image if exists
                if ($area->main_image && file_exists(public_path('areas/images/' . $area->main_image))) {
                    unlink(public_path('areas/images/' . $area->main_image));
                }
                
                $image = $request->file('main_image');
                $imageName = time() . '_' . $image->getClientOriginalName();
                $image->move(public_path('areas/images'), $imageName);
                $data['main_image'] = $imageName;
            }

            $area->update($data);

            // Transform main_image to full URL for response
            if ($area->main_image) {
                $area->main_image = asset('areas/images/' . $area->main_image);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Area updated successfully',
                'data' => $area
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update area',
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
            $area = Area::findOrFail($id);
            $area->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Area deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete area',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Search areas by name.
     */
    public function search(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'q' => 'required|string|min:2'
            ]);

            $searchTerm = $request->q;
            $areas = Area::where('name_ar', 'LIKE', "%{$searchTerm}%")
                        ->orWhere('name_en', 'LIKE', "%{$searchTerm}%")
                        ->get();

            // Transform main_image to full URL
            $areas->each(function ($area) {
                if ($area->main_image) {
                    // Check if main image file exists
                    $mainImagePath = public_path('areas/images/' . $area->main_image);
                    if (file_exists($mainImagePath)) {
                        $area->main_image = asset('areas/images/' . $area->main_image);
                    } else {
                        $area->main_image = null; // Set to null if file doesn't exist
                    }
                }
            });

            return response()->json([
                'status' => 'success',
                'message' => 'Search completed successfully',
                'data' => $areas,
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
     * Get area with projects
     */
    public function getAreaWithProjects(string $id): JsonResponse
    {
        try {
            $area = Area::with(['projects' => function($query) {
                $query->with(['projectImages' => function($imgQuery) {
                    $imgQuery->where('is_featured', true)->orderBy('order', 'asc');
                }]);
            }])->findOrFail($id);

            // Get projects with basic details
            $projects = $area->projects->map(function($project) {
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

            $areaData = [
                'id' => $area->id,
                'name_en' => $area->name_en,
                'name_ar' => $area->name_ar,
                'slug' => $area->slug,
                'main_image' => $area->main_image_url,
                'about_community_overview_ar' => $area->about_community_overview_ar,
                'about_community_overview_en' => $area->about_community_overview_en,
                'rental_sales_trends_ar' => $area->rental_sales_trends_ar,
                'rental_sales_trends_en' => $area->rental_sales_trends_en,
                'roi_ar' => $area->roi_ar,
                'roi_en' => $area->roi_en,
                'things_to_do_perks_ar' => $area->things_to_do_perks_ar,
                'things_to_do_perks_en' => $area->things_to_do_perks_en,
                'projects' => $projects
            ];

            return response()->json([
                'status' => 'success',
                'message' => 'Area with projects retrieved successfully',
                'data' => $areaData
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve area with projects',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
