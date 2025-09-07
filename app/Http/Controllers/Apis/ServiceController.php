<?php

namespace App\Http\Controllers\Apis;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ServiceController extends Controller
{
    /**
     * Display a listing of services.
     */
    public function index(): JsonResponse
    {
        try {
            $services = Service::orderBy('id', 'asc')->get();
            
            // Transform image to full URL
            $services->each(function ($service) {
                if ($service->image) {
                    $service->image = asset('uploads/services/' . $service->image);
                }
            });
            
            return response()->json([
                'status' => 'success',
                'message' => 'Services retrieved successfully',
                'data' => $services
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve services',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created service.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'title_ar' => 'required|string|max:255',
                'title_en' => 'required|string|max:255',
                'description_ar' => 'nullable|string',
                'description_en' => 'nullable|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
                'contact_phone' => 'nullable|string|max:50',
                'request_service' => 'nullable|boolean'
            ]);

            // Handle image upload
            $imageName = null;
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '_service.' . $image->getClientOriginalExtension();
                
                $uploadPath = public_path('uploads/services');
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }
                
                $image->move($uploadPath, $imageName);
            }

            // Create service
            $service = Service::create([
                'title_ar' => $request->title_ar,
                'title_en' => $request->title_en,
                'description_ar' => $request->description_ar,
                'description_en' => $request->description_en,
                'image' => $imageName,
                'contact_phone' => $request->contact_phone,
                'request_service' => $request->has('request_service')
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Service created successfully',
                'data' => $service
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create service',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified service.
     */
    public function show(string $id): JsonResponse
    {
        try {
            $service = Service::findOrFail($id);
            
            return response()->json([
                'status' => 'success',
                'message' => 'Service retrieved successfully',
                'data' => $service
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Service not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Update the specified service.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        try {
            $service = Service::findOrFail($id);
            
            $request->validate([
                'title_ar' => 'sometimes|required|string|max:255',
                'title_en' => 'sometimes|required|string|max:255',
                'description_ar' => 'nullable|string',
                'description_en' => 'nullable|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
                'contact_phone' => 'nullable|string|max:50',
                'request_service' => 'nullable|boolean'
            ]);

            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($service->image && file_exists(public_path('uploads/services/' . $service->image))) {
                    unlink(public_path('uploads/services/' . $service->image));
                }

                $image = $request->file('image');
                $imageName = time() . '_service.' . $image->getClientOriginalExtension();
                
                $uploadPath = public_path('uploads/services');
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }
                
                $image->move($uploadPath, $imageName);
                $service->image = $imageName;
            }

            // Update service
            $service->update([
                'title_ar' => $request->title_ar ?: $service->title_ar,
                'title_en' => $request->title_en ?: $service->title_en,
                'description_ar' => $request->description_ar,
                'description_en' => $request->description_en,
                'contact_phone' => $request->contact_phone,
                'request_service' => $request->has('request_service') ? $request->boolean('request_service') : $service->request_service
            ]);

            // Transform image to full URL
            if ($service->image) {
                $service->image = asset('uploads/services/' . $service->image);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Service updated successfully',
                'data' => $service
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update service',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified service.
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $service = Service::findOrFail($id);
            
            // Delete image if exists
            if ($service->image && file_exists(public_path('uploads/services/' . $service->image))) {
                unlink(public_path('uploads/services/' . $service->image));
            }

            $service->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Service deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete service',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
