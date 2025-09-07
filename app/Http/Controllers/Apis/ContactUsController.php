<?php

namespace App\Http\Controllers\Apis;

use App\Http\Controllers\Controller;
use App\Models\ContactUs;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ContactUsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        try {
            $contactInfo = ContactUs::first();
            
            return response()->json([
                'status' => 'success',
                'message' => 'Contact information retrieved successfully',
                'data' => $contactInfo
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve contact information',
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
                'broker_registration_number' => 'nullable|string|max:100',
                'phone_numbers' => 'required|array|min:1',
                'phone_numbers.*.number' => 'required|string|max:50',
                'phone_numbers.*.type' => 'nullable|string|max:50',
                'email_addresses' => 'required|array|min:1',
                'email_addresses.*.email' => 'required|email|max:100',
                'email_addresses.*.type' => 'nullable|string|max:50',
                'locations' => 'required|array|min:1',
                'locations.*.address_ar' => 'required|string|max:500',
                'locations.*.address_en' => 'required|string|max:500',
                'locations.*.map_embed' => 'nullable|string',
                'map_embed' => 'nullable|string',
            ]);

            $contactInfo = ContactUs::create($request->all());

            return response()->json([
                'status' => 'success',
                'message' => 'Contact information created successfully',
                'data' => $contactInfo
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create contact information',
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
            $contactInfo = ContactUs::findOrFail($id);
            
            return response()->json([
                'status' => 'success',
                'message' => 'Contact information retrieved successfully',
                'data' => $contactInfo
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Contact information not found',
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
            $contactInfo = ContactUs::findOrFail($id);
            
            $request->validate([
                'company_name_ar' => 'sometimes|required|string|max:255',
                'company_name_en' => 'sometimes|required|string|max:255',
                'broker_registration_number' => 'nullable|string|max:100',
                'phone_numbers' => 'sometimes|required|array|min:1',
                'phone_numbers.*.number' => 'required|string|max:50',
                'phone_numbers.*.type' => 'nullable|string|max:50',
                'email_addresses' => 'sometimes|required|array|min:1',
                'email_addresses.*.email' => 'required|email|max:100',
                'email_addresses.*.type' => 'nullable|string|max:50',
                'locations' => 'sometimes|required|array|min:1',
                'locations.*.address_ar' => 'required|string|max:500',
                'locations.*.address_en' => 'required|string|max:500',
                'locations.*.map_embed' => 'nullable|string',
                'map_embed' => 'nullable|string',
            ]);

            $contactInfo->update($request->all());

            return response()->json([
                'status' => 'success',
                'message' => 'Contact information updated successfully',
                'data' => $contactInfo
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update contact information',
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
            $contactInfo = ContactUs::findOrFail($id);
            $contactInfo->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Contact information deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete contact information',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
