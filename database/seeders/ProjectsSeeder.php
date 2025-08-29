<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Project;
use App\Models\Developer;
use App\Models\Area;

class ProjectsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get developers and areas
        $developers = Developer::all();
        $areas = Area::all();

        if ($developers->isEmpty() || $areas->isEmpty()) {
            $this->command->error('Please run DeveloperAreaSeeder first!');
            return;
        }

        $projects = [
            [
                'prj_title_ar' => 'برج خليفة ريزيدنس',
                'prj_title_en' => 'Burj Khalifa Residences',
                'prj_description_ar' => 'واحدة من أرقى المشاريع السكنية في قلب دبي، تتميز بإطلالات خلابة على برج خليفة ونافورة دبي. شقق فاخرة بتصاميم عصرية وخدمات 5 نجوم.',
                'prj_description_en' => 'One of the most prestigious residential projects in the heart of Dubai, featuring stunning views of Burj Khalifa and Dubai Fountain. Luxury apartments with modern designs and 5-star services.',
                'prj_developerId' => $developers->where('name_en', 'Emaar Properties')->first()->id,
                'prj_areaId' => $areas->where('name_en', 'Downtown Dubai')->first()->id,
                'prj_projectNumber' => 'BK-001',
                'prj_adm' => 'ADM-2024-001',
                'prj_cn' => 'CN-2024-001',
                'prj_MadhmounPermitNumber' => 'MP-2024-001'
            ],
            [
                'prj_title_ar' => 'مرسى دبي تاورز',
                'prj_title_en' => 'Dubai Marina Towers',
                'prj_description_ar' => 'مجمع سكني فاخر في مرسى دبي مع إطلالات مباشرة على البحر. شقق وأجنحة فاخرة مع مرافق ترفيهية متكاملة وخدمات عالية الجودة.',
                'prj_description_en' => 'Luxury residential complex in Dubai Marina with direct sea views. Luxury apartments and suites with integrated recreational facilities and high-quality services.',
                'prj_developerId' => $developers->where('name_en', 'Nakheel Properties')->first()->id,
                'prj_areaId' => $areas->where('name_en', 'Dubai Marina')->first()->id,
                'prj_projectNumber' => 'DM-002',
                'prj_adm' => 'ADM-2024-002',
                'prj_cn' => 'CN-2024-002',
                'prj_MadhmounPermitNumber' => 'MP-2024-002'
            ],
            [
                'prj_title_ar' => 'جزيرة نخلة الجميرة فيلات',
                'prj_title_en' => 'Palm Jumeirah Villas',
                'prj_description_ar' => 'فيلات فاخرة على جزيرة نخلة الجميرة مع إطلالات خلابة على الخليج العربي. تصميم عصري مع حدائق خاصة وممرات خاصة للقوارب.',
                'prj_description_en' => 'Luxury villas on Palm Jumeirah with stunning views of the Arabian Gulf. Modern design with private gardens and private boat access.',
                'prj_developerId' => $developers->where('name_en', 'Nakheel Properties')->first()->id,
                'prj_areaId' => $areas->where('name_en', 'Palm Jumeirah')->first()->id,
                'prj_projectNumber' => 'PJ-003',
                'prj_adm' => 'ADM-2024-003',
                'prj_cn' => 'CN-2024-003',
                'prj_MadhmounPermitNumber' => 'MP-2024-003'
            ],
            [
                'prj_title_ar' => 'خليج الأعمال بلازا',
                'prj_title_en' => 'Business Bay Plaza',
                'prj_description_ar' => 'مجمع تجاري وسكني في قلب خليج الأعمال. مكاتب فاخرة وشقق سكنية مع مرافق تجارية متكاملة وموقف سيارات تحت الأرض.',
                'prj_description_en' => 'Commercial and residential complex in the heart of Business Bay. Luxury offices and residential apartments with integrated commercial facilities and underground parking.',
                'prj_developerId' => $developers->where('name_en', 'Dubai Properties')->first()->id,
                'prj_areaId' => $areas->where('name_en', 'Business Bay')->first()->id,
                'prj_projectNumber' => 'BB-004',
                'prj_adm' => 'ADM-2024-004',
                'prj_cn' => 'CN-2024-004',
                'prj_MadhmounPermitNumber' => 'MP-2024-004'
            ],
            [
                'prj_title_ar' => 'إقامة شاطئ الجميرة',
                'prj_title_en' => 'Jumeirah Beach Residence',
                'prj_description_ar' => 'مجمع سكني فاخر على شاطئ الجميرة مع إطلالات مباشرة على البحر. شقق وأجنحة فاخرة مع مرافق ترفيهية متكاملة.',
                'prj_description_en' => 'Luxury residential complex on Jumeirah Beach with direct sea views. Luxury apartments and suites with integrated recreational facilities.',
                'prj_developerId' => $developers->where('name_en', 'Emaar Properties')->first()->id,
                'prj_areaId' => $areas->where('name_en', 'Jumeirah Beach Residence')->first()->id,
                'prj_projectNumber' => 'JBR-005',
                'prj_adm' => 'ADM-2024-005',
                'prj_cn' => 'CN-2024-005',
                'prj_MadhmounPermitNumber' => 'MP-2024-005'
            ],
            [
                'prj_title_ar' => 'عقارات تلال دبي',
                'prj_title_en' => 'Dubai Hills Estate',
                'prj_description_ar' => 'مجتمع سكني فاخر في تلال دبي مع حدائق خضراء واسعة ومرافق رياضية متكاملة. شقق وفيلات فاخرة في بيئة طبيعية هادئة.',
                'prj_description_en' => 'Luxury residential community in Dubai Hills with extensive green gardens and integrated sports facilities. Luxury apartments and villas in a peaceful natural environment.',
                'prj_developerId' => $developers->where('name_en', 'Emaar Properties')->first()->id,
                'prj_areaId' => $areas->where('name_en', 'Dubai Hills Estate')->first()->id,
                'prj_projectNumber' => 'DHE-006',
                'prj_adm' => 'ADM-2024-006',
                'prj_cn' => 'CN-2024-006',
                'prj_MadhmounPermitNumber' => 'MP-2024-006'
            ],
            [
                'prj_title_ar' => 'مزارع العرب ريزيدنس',
                'prj_title_en' => 'Arabian Ranches Residences',
                'prj_description_ar' => 'فيلات فاخرة في مزارع العرب مع حدائق خاصة واسعة ومرافق رياضية متكاملة. تصميم كلاسيكي مع لمسة عصرية.',
                'prj_description_en' => 'Luxury villas in Arabian Ranches with extensive private gardens and integrated sports facilities. Classic design with a modern touch.',
                'prj_developerId' => $developers->where('name_en', 'Emaar Properties')->first()->id,
                'prj_areaId' => $areas->where('name_en', 'Arabian Ranches')->first()->id,
                'prj_projectNumber' => 'AR-007',
                'prj_adm' => 'ADM-2024-007',
                'prj_cn' => 'CN-2024-007',
                'prj_MadhmounPermitNumber' => 'MP-2024-007'
            ],
            [
                'prj_title_ar' => 'تلال الإمارات فيلات',
                'prj_title_en' => 'Emirates Hills Villas',
                'prj_description_ar' => 'فيلات فاخرة في تلال الإمارات مع إطلالات خلابة على المدينة. تصميم فريد مع حدائق خاصة وممرات خاصة.',
                'prj_description_en' => 'Luxury villas in Emirates Hills with stunning city views. Unique design with private gardens and private access.',
                'prj_developerId' => $developers->where('name_en', 'Emaar Properties')->first()->id,
                'prj_areaId' => $areas->where('name_en', 'Emirates Hills')->first()->id,
                'prj_projectNumber' => 'EH-008',
                'prj_adm' => 'ADM-2024-008',
                'prj_cn' => 'CN-2024-008',
                'prj_MadhmounPermitNumber' => 'MP-2024-008'
            ],
            [
                'prj_title_ar' => 'مرسى دبي سكاي تاور',
                'prj_title_en' => 'Dubai Marina Sky Tower',
                'prj_description_ar' => 'برج سكني فاخر في مرسى دبي مع إطلالات خلابة على البحر والمدينة. شقق فاخرة مع مرافق ترفيهية متكاملة.',
                'prj_description_en' => 'Luxury residential tower in Dubai Marina with stunning sea and city views. Luxury apartments with integrated recreational facilities.',
                'prj_developerId' => $developers->where('name_en', 'Sobha Realty')->first()->id,
                'prj_areaId' => $areas->where('name_en', 'Dubai Marina')->first()->id,
                'prj_projectNumber' => 'DMST-009',
                'prj_adm' => 'ADM-2024-009',
                'prj_cn' => 'CN-2024-009',
                'prj_MadhmounPermitNumber' => 'MP-2024-009'
            ],
            [
                'prj_title_ar' => 'وسط مدينة دبي بلازا',
                'prj_title_en' => 'Downtown Dubai Plaza',
                'prj_description_ar' => 'مجمع تجاري وسكني في قلب وسط مدينة دبي. مكاتب فاخرة وشقق سكنية مع مرافق تجارية متكاملة.',
                'prj_description_en' => 'Commercial and residential complex in the heart of Downtown Dubai. Luxury offices and residential apartments with integrated commercial facilities.',
                'prj_developerId' => $developers->where('name_en', 'Meraas')->first()->id,
                'prj_areaId' => $areas->where('name_en', 'Downtown Dubai')->first()->id,
                'prj_projectNumber' => 'DDP-010',
                'prj_adm' => 'ADM-2024-010',
                'prj_cn' => 'CN-2024-010',
                'prj_MadhmounPermitNumber' => 'MP-2024-010'
            ]
        ];

        foreach ($projects as $projectData) {
            Project::create($projectData);
        }

        $this->command->info('✅ 10 luxury real estate projects have been created successfully!');
        $this->command->info('📍 Projects include: Downtown Dubai, Dubai Marina, Palm Jumeirah, Business Bay, and more!');
    }
}
