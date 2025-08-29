<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Project;
use App\Models\ProjectDetail;

class ProjectDetailsSeeder extends Seeder
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

        foreach ($projects as $project) {
            // Sample project details based on project type
            $details = [
                [
                    'detail_ar' => 'السعر الابتدائي',
                    'detail_en' => 'Starting Price',
                    'detail_value_ar' => 'قريباً',
                    'detail_value_en' => 'Coming Soon',
                    'order' => 1
                ],
                [
                    'detail_ar' => 'نوع العرض',
                    'detail_en' => 'Offering type',
                    'detail_value_ar' => 'شقق من 2 إلى 5 غرف نوم',
                    'detail_value_en' => '2 to 5 Bedroom Apartments',
                    'order' => 2
                ],
                [
                    'detail_ar' => 'نوع الوحدات',
                    'detail_en' => 'Unit type',
                    'detail_value_ar' => 'شقق مفروشة ودوبلكس',
                    'detail_value_en' => 'Furnished Apartments & Duplex',
                    'order' => 3
                ],
                [
                    'detail_ar' => 'خطة الدفع',
                    'detail_en' => 'Payment Plan',
                    'detail_value_ar' => 'سيتم الإعلان قريباً',
                    'detail_value_en' => 'TBA',
                    'order' => 4
                ],
                [
                    'detail_ar' => 'المطور',
                    'detail_en' => 'Developer',
                    'detail_value_ar' => $project->developer ? (app()->getLocale() == 'ar' ? $project->developer->name_ar : $project->developer->name_en) : 'غير محدد',
                    'detail_value_en' => $project->developer ? $project->developer->name_en : 'Not specified',
                    'order' => 5
                ],
                [
                    'detail_ar' => 'تاريخ التسليم',
                    'detail_en' => 'Handover',
                    'detail_value_ar' => 'الربع الثاني 2029',
                    'detail_value_en' => 'Q2 2029',
                    'order' => 6
                ],
                [
                    'detail_ar' => 'الموقع',
                    'detail_en' => 'Location',
                    'detail_value_ar' => $project->area ? (app()->getLocale() == 'ar' ? $project->area->name_ar : $project->area->name_en) : 'غير محدد',
                    'detail_value_en' => $project->area ? $project->area->name_en : 'Not specified',
                    'order' => 7
                ],
                [
                    'detail_ar' => 'نسبة الإنجاز',
                    'detail_en' => 'Completion Rate',
                    'detail_value_ar' => '25%',
                    'detail_value_en' => '25%',
                    'order' => 8
                ],
                [
                    'detail_ar' => 'عدد الطوابق',
                    'detail_en' => 'Number of Floors',
                    'detail_value_ar' => '25 طابق',
                    'detail_value_en' => '25 Floors',
                    'order' => 9
                ],
                [
                    'detail_ar' => 'مساحة الوحدات',
                    'detail_en' => 'Unit Area',
                    'detail_value_ar' => 'من 800 إلى 2500 قدم مربع',
                    'detail_value_en' => '800 to 2500 sq ft',
                    'order' => 10
                ]
            ];

            foreach ($details as $detailData) {
                ProjectDetail::create([
                    'project_id' => $project->id,
                    'detail_ar' => $detailData['detail_ar'],
                    'detail_en' => $detailData['detail_en'],
                    'detail_value_ar' => $detailData['detail_value_ar'],
                    'detail_value_en' => $detailData['detail_value_en'],
                    'order' => $detailData['order']
                ]);
            }
        }

        $this->command->info('✅ Project details have been created successfully for all projects!');
        $this->command->info('📍 Each project now has 10 detailed specifications');
    }
}
