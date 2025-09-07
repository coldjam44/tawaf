<?php

namespace App\Http\Controllers\Apis;

use App\Http\Controllers\Controller;
use App\Models\RealEstateCompany;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class RealEstateCompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        try {
            $companies = RealEstateCompany::orderBy('company_name_' . app()->getLocale())->get();
            
            // Transform company_logo to full URL
            $companies->each(function ($company) {
                if ($company->company_logo) {
                    $company->company_logo = asset('real-estate-companies/' . $company->company_logo);
                }
            });
            
            return response()->json([
                'status' => 'success',
                'message' => 'Real estate companies retrieved successfully',
                'data' => $companies
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve real estate companies',
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
                'company_name_ar' => 'required|string|max:255',
                'company_name_en' => 'required|string|max:255',
                'short_description_ar' => 'nullable|string',
                'short_description_en' => 'nullable|string',
                'about_company_ar' => 'nullable|string',
                'about_company_en' => 'nullable|string',
                'contact_number' => 'nullable|string|max:50',
                'features_ar' => 'nullable|string',
                'features_en' => 'nullable|string',
                'properties_communities_ar' => 'nullable|string',
                'properties_communities_en' => 'nullable|string',
                'adm_number' => 'nullable|string|max:100',
                'cn_number' => 'nullable|string|max:100',
                'company_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif',
                'company_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            ]);

            $company = RealEstateCompany::create($request->except(['company_logo', 'company_images']));

            // Handle company logo upload
            if ($request->hasFile('company_logo')) {
                $logoName = time() . '_logo.' . $request->company_logo->extension();
                $request->company_logo->move(public_path('real-estate-companies'), $logoName);
                $company->company_logo = $logoName;
                $company->save();
            }

            // Handle company images upload
            if ($request->hasFile('company_images')) {
                $imageNames = [];
                foreach ($request->file('company_images') as $image) {
                    $imageName = time() . '_' . uniqid() . '.' . $image->extension();
                    $image->move(public_path('real-estate-companies'), $imageName);
                    $imageNames[] = $imageName;
                }
                $company->company_images = $imageNames;
                $company->save();
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Real estate company created successfully',
                'data' => $company
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create real estate company',
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
            $company = RealEstateCompany::with(['developers', 'projects'])->findOrFail($id);
            
            return response()->json([
                'status' => 'success',
                'message' => 'Real estate company retrieved successfully',
                'data' => $company
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Real estate company not found',
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
            $company = RealEstateCompany::findOrFail($id);
            
            $request->validate([
                'company_name_ar' => 'sometimes|required|string|max:255',
                'company_name_en' => 'sometimes|required|string|max:255',
                'short_description_ar' => 'nullable|string',
                'short_description_en' => 'nullable|string',
                'about_company_ar' => 'nullable|string',
                'about_company_en' => 'nullable|string',
                'contact_number' => 'nullable|string|max:50',
                'features_ar' => 'nullable|string',
                'features_en' => 'nullable|string',
                'properties_communities_ar' => 'nullable|string',
                'properties_communities_en' => 'nullable|string',
                'adm_number' => 'nullable|string|max:100',
                'cn_number' => 'nullable|string|max:100',
                'company_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif',
                'company_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            ]);

            $company->update($request->except(['company_logo', 'company_images']));

            // Handle company logo upload
            if ($request->hasFile('company_logo')) {
                // Delete old logo if exists
                if ($company->company_logo && file_exists(public_path('real-estate-companies/' . $company->company_logo))) {
                    unlink(public_path('real-estate-companies/' . $company->company_logo));
                }
                
                $logoName = time() . '_logo.' . $request->company_logo->extension();
                $request->company_logo->move(public_path('real-estate-companies'), $logoName);
                $company->company_logo = $logoName;
                $company->save();
            }

            // Handle company images upload
            if ($request->hasFile('company_images')) {
                // Delete old images if exist
                if ($company->company_images) {
                    foreach ($company->company_images as $oldImage) {
                        if (file_exists(public_path('real-estate-companies/' . $oldImage))) {
                            unlink(public_path('real-estate-companies/' . $oldImage));
                        }
                    }
                }
                
                $imageNames = [];
                foreach ($request->file('company_images') as $image) {
                    $imageName = time() . '_' . uniqid() . '.' . $image->extension();
                    $image->move(public_path('real-estate-companies'), $imageName);
                    $imageNames[] = $imageName;
                }
                $company->company_images = $imageNames;
                $company->save();
            }

            // Transform company_logo to full URL
            if ($company->company_logo) {
                $company->company_logo = asset('real-estate-companies/' . $company->company_logo);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Real estate company updated successfully',
                'data' => $company
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update real estate company',
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
            $company = RealEstateCompany::findOrFail($id);
            
            // Delete company logo if exists
            if ($company->company_logo && file_exists(public_path('real-estate-companies/' . $company->company_logo))) {
                unlink(public_path('real-estate-companies/' . $company->company_logo));
            }
            
            // Delete company images if exist
            if ($company->company_images) {
                foreach ($company->company_images as $image) {
                    if (file_exists(public_path('real-estate-companies/' . $image))) {
                        unlink(public_path('real-estate-companies/' . $image));
                    }
                }
            }
            
            $company->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Real estate company deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete real estate company',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Search real estate companies by name or description.
     */
    public function search(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'q' => 'required|string|min:2'
            ]);

            $searchTerm = $request->q;
            $companies = RealEstateCompany::where('company_name_ar', 'LIKE', "%{$searchTerm}%")
                                        ->orWhere('company_name_en', 'LIKE', "%{$searchTerm}%")
                                        ->orWhere('short_description_ar', 'LIKE', "%{$searchTerm}%")
                                        ->orWhere('short_description_en', 'LIKE', "%{$searchTerm}%")
                                        ->orWhere('about_company_ar', 'LIKE', "%{$searchTerm}%")
                                        ->orWhere('about_company_en', 'LIKE', "%{$searchTerm}%")
                                        ->orWhere('contact_number', 'LIKE', "%{$searchTerm}%")
                                        ->orWhere('adm_number', 'LIKE', "%{$searchTerm}%")
                                        ->orWhere('cn_number', 'LIKE', "%{$searchTerm}%")
                                        ->get();

            return response()->json([
                'status' => 'success',
                'message' => 'Search completed successfully',
                'data' => $companies,
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
}
