<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ExpertTeam;

class ExpertTeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $members = [
            [
                'name_ar' => 'أحمد محمد علي',
                'name_en' => 'Ahmed Mohamed Ali',
                'position_ar' => 'الرئيس التنفيذي',
                'position_en' => 'Chief Executive Officer',
                'order_index' => 1
            ],
            [
                'name_ar' => 'فاطمة أحمد حسن',
                'name_en' => 'Fatima Ahmed Hassan',
                'position_ar' => 'مدير التطوير العقاري',
                'position_en' => 'Real Estate Development Manager',
                'order_index' => 2
            ],
            [
                'name_ar' => 'محمد سعيد عبدالله',
                'name_en' => 'Mohamed Saeed Abdullah',
                'position_ar' => 'مدير المبيعات',
                'position_en' => 'Sales Manager',
                'order_index' => 3
            ],
            [
                'name_ar' => 'سارة خالد محمد',
                'name_en' => 'Sarah Khalid Mohamed',
                'position_ar' => 'مدير التسويق',
                'position_en' => 'Marketing Manager',
                'order_index' => 4
            ],
            [
                'name_ar' => 'علي حسن محمود',
                'name_en' => 'Ali Hassan Mahmoud',
                'position_ar' => 'مدير المشاريع',
                'position_en' => 'Project Manager',
                'order_index' => 5
            ]
        ];

        foreach ($members as $memberData) {
            ExpertTeam::create($memberData);
        }
    }
}
