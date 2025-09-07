<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;

class BotOffersController extends Controller
{
    /**
     * Display a listing of all projects for bot offers management.
     */
    public function index()
    {
        $projects = Project::with(['developer', 'area', 'projectImages'])
            ->orderBy('id', 'desc')
            ->get();

        // Prepare images data for JavaScript
        $imagesData = $projects->keyBy('id')->map(function($project) {
            return $project->projectImages->map(function($image) {
                return [
                    'id' => $image->id,
                    'path' => $image->image_path,
                    'type' => $image->type,
                    'title' => $image->getTitle(),
                    'description' => $image->getDescription(),
                    'is_featured' => $image->is_featured,
                    'order' => $image->order
                ];
            });
        });

        return view('bot-offers.index', compact('projects', 'imagesData'));
    }

    /**
     * Toggle bot status for a project
     */
    public function toggle(Request $request, $id)
    {
        try {
            $project = Project::findOrFail($id);
            
            // Toggle the current bot status
            $newStatus = !$project->is_sent_to_bot;
            
            // Update project in database
            $project->update(['is_sent_to_bot' => $newStatus]);
            
            // Log the action for future reference
            \Log::info("Project {$project->id} bot status toggled to: " . ($newStatus ? 'true' : 'false'));
            
            $message = $newStatus ? 
                'تم إضافة المشروع لقائمة عروض البوت بنجاح' : 
                'تم إزالة المشروع من قائمة عروض البوت';
            
            return redirect()->back()->with('success', $message);
            
        } catch (\Exception $e) {
            \Log::error("Error toggling bot status for project {$id}: " . $e->getMessage());
            
            return redirect()->back()->with('error', 'حدث خطأ: ' . $e->getMessage());
        }
    }

    /**
     * Get projects that are sent to bot (for API use)
     */
    public function getBotProjects()
    {
        try {
            $projects = Project::with(['developer', 'area', 'projectImages'])
                ->where('is_sent_to_bot', true)
                ->orderBy('id', 'desc')
                ->get();

            // Format the data for bot consumption - only display texts
            $formattedProjects = $projects->map(function($project) {
                $titleAr = $project->prj_title_ar ?: $project->prj_title_en;
                $titleEn = $project->prj_title_en ?: $project->prj_title_ar;
                $areaAr = $project->area ? ($project->area->name_ar ?: $project->area->name_en) : 'غير محدد';
                $areaEn = $project->area ? ($project->area->name_en ?: $project->area->name_ar) : 'Not Specified';
                
                return [
                    'display_text_ar' => $titleAr . ' في ' . $areaAr,
                    'display_text_en' => $titleEn . ' in ' . $areaEn
                ];
            });

            return response()->json([
                'success' => true,
                'message' => 'Bot offers retrieved successfully',
                'data' => $formattedProjects,
                'count' => $formattedProjects->count(),
                'timestamp' => now()->toISOString()
            ]);

        } catch (\Exception $e) {
            \Log::error("Error fetching bot projects: " . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving bot offers',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
