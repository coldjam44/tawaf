<?php

namespace App\Http\Controllers\Apis;

use App\Http\Controllers\Controller;
use App\Models\Newsletter;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class NewsletterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $newsletters = Newsletter::latest()->paginate($request->get('per_page', 10));
            
            return response()->json([
                'status' => 'success',
                'message' => 'Newsletters retrieved successfully',
                'data' => $newsletters
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve newsletters',
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
                'name' => 'nullable|string|max:255',
                'email' => 'nullable|email|max:255',
                'phone' => 'nullable|string|max:50',
                'message' => 'nullable|string',
            ]);

            $newsletter = Newsletter::create($request->all());

            return response()->json([
                'status' => 'success',
                'message' => 'Newsletter subscription created successfully',
                'data' => $newsletter
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create newsletter subscription',
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
            $newsletter = Newsletter::findOrFail($id);
            
            return response()->json([
                'status' => 'success',
                'message' => 'Newsletter subscription retrieved successfully',
                'data' => $newsletter
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Newsletter subscription not found',
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
            $newsletter = Newsletter::findOrFail($id);
            
            $request->validate([
                'name' => 'sometimes|nullable|string|max:255',
                'email' => 'sometimes|nullable|email|max:255',
                'phone' => 'sometimes|nullable|string|max:50',
                'message' => 'sometimes|nullable|string',
            ]);

            $newsletter->update($request->all());

            return response()->json([
                'status' => 'success',
                'message' => 'Newsletter subscription updated successfully',
                'data' => $newsletter
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update newsletter subscription',
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
            $newsletter = Newsletter::findOrFail($id);
            $newsletter->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Newsletter subscription deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete newsletter subscription',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
