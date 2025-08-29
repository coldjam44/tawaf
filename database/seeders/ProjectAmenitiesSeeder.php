<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Project;
use App\Models\ProjectAmenity;

class ProjectAmenitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $projects = Project::all();

        if ($projects->isEmpty()) {
            $this->command->error('Please run ProjectsSeeder first!');
            return;
        }

        // Common amenities that most luxury projects have
        $commonAmenities = [
            'infinity_pool',
            'gym',
            'concierge_services',
            'retail_fnb'
        ];

        // Additional amenities for premium projects
        $premiumAmenities = [
            'bbq_area',
            'cinema_game_room',
            'wellness_facilities',
            'sauna_wellness'
        ];

        // Family-friendly amenities
        $familyAmenities = [
            'splash_pad',
            'multipurpose_court'
        ];

        foreach ($projects as $index => $project) {
            $this->addProjectAmenities($project, $index, $commonAmenities, $premiumAmenities, $familyAmenities);
        }

        $this->command->info('✅ Project amenities have been created successfully!');
        $this->command->info('📍 Each project now has various amenities');
    }

    private function addProjectAmenities($project, $index, $commonAmenities, $premiumAmenities, $familyAmenities)
    {
        // All projects get common amenities
        $amenitiesToAdd = $commonAmenities;

        // Add premium amenities for even-indexed projects (every other project)
        if ($index % 2 == 0) {
            $amenitiesToAdd = array_merge($amenitiesToAdd, $premiumAmenities);
        }

        // Add family amenities for projects with index divisible by 3
        if ($index % 3 == 0) {
            $amenitiesToAdd = array_merge($amenitiesToAdd, $familyAmenities);
        }

        // Randomly deactivate some amenities (20% chance)
        foreach ($amenitiesToAdd as $amenityType) {
            $isActive = rand(1, 100) > 20; // 80% chance of being active

            ProjectAmenity::create([
                'project_id' => $project->id,
                'amenity_type' => $amenityType,
                'is_active' => $isActive
            ]);
        }
    }
}
