<?php

namespace App\Http\Controllers;

use App\Services\LocationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VisitorLocationController extends Controller
{
    protected $locationService;

    public function __construct(LocationService $locationService)
    {
        $this->locationService = $locationService;
    }

    /**
     * Get visitor's country information
     */
    public function getVisitorCountry(Request $request): JsonResponse
    {
        try {
            // Get the visitor's location data
            $locationData = $this->locationService->getVisitorCountry();

            return response()->json([
                'success' => true,
                'data' => $locationData,
                'timestamp' => now()->toISOString()
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Unable to determine visitor location',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Get country for a specific IP address
     */
    public function getCountryByIP(Request $request, string $ip): JsonResponse
    {
        // Validate IP address
        if (!filter_var($ip, FILTER_VALIDATE_IP)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid IP address format'
            ], 400);
        }

        try {
            $locationData = $this->locationService->getVisitorCountry($ip);

            return response()->json([
                'success' => true,
                'data' => $locationData,
                'timestamp' => now()->toISOString()
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Unable to determine location for the provided IP',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Get multiple visitor details (country, timezone, etc.)
     */
    public function getVisitorDetails(Request $request): JsonResponse
    {
        try {
            $locationData = $this->locationService->getVisitorCountry();
            
            // Add additional visitor information
            $visitorDetails = array_merge($locationData, [
                'user_agent' => $request->userAgent(),
                'accept_language' => $request->header('Accept-Language'),
                'is_mobile' => $request->header('User-Agent') && 
                              preg_match('/Mobile|Android|iPhone|iPad/', $request->header('User-Agent')),
                'timestamp' => now()->toISOString()
            ]);

            return response()->json([
                'success' => true,
                'data' => $visitorDetails
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Unable to gather visitor details',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }
}