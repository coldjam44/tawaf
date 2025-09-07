<?php

namespace App\Http\Controllers\Apis;

use App\Http\Controllers\Controller;
use App\Models\Developer;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class DeveloperController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        try {
            $developers = Developer::orderBy('name_' . app()->getLocale())->get();
            
            // Transform image to full URL
            $developers->each(function ($developer) {
                if ($developer->image) {
                    $developer->image = asset('developers/' . $developer->image);
                }
            });
            
            return response()->json([
                'status' => 'success',
                'message' => 'Developers retrieved successfully',
                'data' => $developers
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve developers',
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
                'email' => 'nullable|email|max:255',
                'phone' => 'nullable|string|max:50',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif',
                'company_id' => 'nullable|exists:real_estate_companies,id',
            ]);

            $developer = Developer::create($request->except(['image']));

            if ($request->hasFile('image')) {
                $imageName = time() . '_developer.' . $request->image->extension();
                $request->image->move(public_path('developers'), $imageName);
                $developer->image = $imageName;
                $developer->save();
            }

            // Transform image to full URL
            if ($developer->image) {
                $developer->image = asset('developers/' . $developer->image);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Developer created successfully',
                'data' => $developer
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create developer',
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
            $developer = Developer::findOrFail($id);
            
            // Transform image to full URL
            if ($developer->image) {
                $developer->image = asset('developers/' . $developer->image);
            }
            
            return response()->json([
                'status' => 'success',
                'message' => 'Developer retrieved successfully',
                'data' => $developer
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Developer not found',
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
            $developer = Developer::findOrFail($id);
            
            $request->validate([
                'name_ar' => 'sometimes|required|string|max:255',
                'name_en' => 'sometimes|required|string|max:255',
                'email' => 'nullable|email|max:255',
                'phone' => 'nullable|string|max:50',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif',
                'company_id' => 'nullable|exists:real_estate_companies,id',
            ]);

            $developer->update($request->except(['image']));

            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($developer->image && file_exists(public_path('developers/' . $developer->image))) {
                    unlink(public_path('developers/' . $developer->image));
                }
                
                $imageName = time() . '_developer.' . $request->image->extension();
                $request->image->move(public_path('developers'), $imageName);
                $developer->image = $imageName;
                $developer->save();
            }

            // Transform image to full URL
            if ($developer->image) {
                $developer->image = asset('developers/' . $developer->image);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Developer updated successfully',
                'data' => $developer
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update developer',
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
            $developer = Developer::findOrFail($id);
            
            // Delete image if exists
            if ($developer->image && file_exists(public_path('developers/' . $developer->image))) {
                unlink(public_path('developers/' . $developer->image));
            }
            
            $developer->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Developer deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete developer',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Search developers by name or description.
     */
    public function search(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'q' => 'required|string|min:2'
            ]);

            $searchTerm = $request->q;
            $developers = Developer::where('name_ar', 'LIKE', "%{$searchTerm}%")
                                 ->orWhere('name_en', 'LIKE', "%{$searchTerm}%")
                                 ->orWhere('email', 'LIKE', "%{$searchTerm}%")
                                 ->orWhere('phone', 'LIKE', "%{$searchTerm}%")
                                 ->get();

            return response()->json([
                'status' => 'success',
                'message' => 'Search completed successfully',
                'data' => $developers,
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
