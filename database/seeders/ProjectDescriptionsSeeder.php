<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Project;
use App\Models\ProjectDescription;

class ProjectDescriptionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $projects = Project::with('developer', 'area')->get();

        if ($projects->isEmpty()) {
            $this->command->error('Please run ProjectsSeeder first!');
            return;
        }

        foreach ($projects as $project) {
            $this->addProjectDescriptions($project);
        }

        $this->command->info('✅ Project descriptions have been created successfully!');
        $this->command->info('📍 Each project now has detailed descriptions');
    }

    private function addProjectDescriptions($project)
    {
        $descriptions = [
            [
                'section_type' => 'about',
                'title_ar' => 'عن مشروع ' . $project->prj_title_ar,
                'title_en' => 'About ' . $project->prj_title_en,
                'content_ar' => $this->getAboutContent($project, 'ar'),
                'content_en' => $this->getAboutContent($project, 'en'),
                'order_index' => 1
            ],
            [
                'section_type' => 'architecture',
                'title_ar' => 'التميز المعماري والتصميم المستدام',
                'title_en' => 'Architectural Excellence & Sustainable Design',
                'content_ar' => $this->getArchitectureContent($project, 'ar'),
                'content_en' => $this->getArchitectureContent($project, 'en'),
                'order_index' => 2
            ],
            [
                'section_type' => 'why_choose',
                'title_ar' => 'لماذا تختار ' . $project->prj_title_ar,
                'title_en' => 'Why Choose ' . $project->prj_title_en,
                'content_ar' => $this->getWhyChooseContent($project, 'ar'),
                'content_en' => $this->getWhyChooseContent($project, 'en'),
                'order_index' => 3
            ],
            [
                'section_type' => 'location',
                'title_ar' => 'الموقع والاتصال',
                'title_en' => 'Location & Connectivity',
                'content_ar' => $this->getLocationContent($project, 'ar'),
                'content_en' => $this->getLocationContent($project, 'en'),
                'order_index' => 4
            ],
            [
                'section_type' => 'investment',
                'title_ar' => 'مزايا الاستثمار',
                'title_en' => 'Investment Benefits',
                'content_ar' => $this->getInvestmentContent($project, 'ar'),
                'content_en' => $this->getInvestmentContent($project, 'en'),
                'order_index' => 5
            ]
        ];

        foreach ($descriptions as $description) {
            ProjectDescription::create([
                'project_id' => $project->id,
                'section_type' => $description['section_type'],
                'title_ar' => $description['title_ar'],
                'title_en' => $description['title_en'],
                'content_ar' => $description['content_ar'],
                'content_en' => $description['content_en'],
                'order_index' => $description['order_index'],
                'is_active' => true
            ]);
        }
    }

    private function getAboutContent($project, $locale)
    {
        $areaName = $locale == 'ar' ? $project->area->name_ar : $project->area->name_en;
        $developerName = $locale == 'ar' ? $project->developer->name_ar : $project->developer->name_en;
        $projectName = $locale == 'ar' ? $project->prj_title_ar : $project->prj_title_en;

        if ($locale == 'ar') {
            return "<p>يقع مشروع <strong>{$projectName}</strong> في قلب {$areaName}، وهو أحد أكثر المناطق حيوية وجاذبية في الإمارات العربية المتحدة. تم تطوير هذا المشروع الفاخر من قبل <strong>{$developerName}</strong>، أحد أبرز المطورين العقاريين في المنطقة.</p>

<p>يتميز المشروع بموقعه الاستراتيجي المثالي، حيث يوفر سهولة الوصول إلى جميع الخدمات والمرافق الأساسية، مع الحفاظ على الهدوء والخصوصية التي يحتاجها سكانه. تم تصميم المشروع ليلبي أعلى معايير الجودة والرفاهية، مما يجعله خياراً مثالياً للعائلات والأفراد الباحثين عن نمط حياة استثنائي.</p>

<p>يضم المشروع مجموعة متنوعة من الوحدات السكنية المصممة بعناية فائقة، من الشقق المريحة إلى الفيلات الفاخرة، كل منها يتميز بتصميم عصري ومرافق متطورة تضمن تجربة سكنية لا تُنسى.</p>";
        } else {
            return "<p><strong>{$projectName}</strong> is strategically located in the heart of {$areaName}, one of the most vibrant and attractive areas in the United Arab Emirates. This luxury project is developed by <strong>{$developerName}</strong>, one of the region's leading real estate developers.</p>

<p>The project features an ideal strategic location, providing easy access to all essential services and facilities while maintaining the tranquility and privacy that its residents need. The project is designed to meet the highest standards of quality and luxury, making it an ideal choice for families and individuals seeking an exceptional lifestyle.</p>

<p>The project includes a diverse range of carefully designed residential units, from comfortable apartments to luxury villas, each featuring modern design and advanced facilities that ensure an unforgettable living experience.</p>";
        }
    }

    private function getArchitectureContent($project, $locale)
    {
        $projectName = $locale == 'ar' ? $project->prj_title_ar : $project->prj_title_en;

        if ($locale == 'ar') {
            return "<h4>التصميم المعماري المتميز</h4>
<p>يتميز <strong>{$projectName}</strong> بتصميم معماري عصري يجمع بين الأناقة والوظائفية، حيث تم تطبيق أحدث التقنيات والمبادئ المعمارية المستدامة لإنشاء مساحات معيشية استثنائية.</p>

<h5>المميزات المعمارية:</h5>
<ul>
<li><strong>تصميم مستدام:</strong> استخدام مواد صديقة للبيئة وتقنيات توفير الطاقة</li>
<li><strong>نوافذ كبيرة:</strong> تسمح بدخول الضوء الطبيعي وتوفر إطلالات خلابة</li>
<li><strong>شرفات واسعة:</strong> مساحات خارجية مثالية للاسترخاء والترفيه</li>
<li><strong>أسقف عالية:</strong> تخلق إحساساً بالاتساع والراحة</li>
<li><strong>تصميم ذكي:</strong> استغلال أمثل للمساحات والموارد</li>
</ul>

<h5>المواد والتشطيبات:</h5>
<p>تم استخدام أجود أنواع المواد والتشطيبات العالية الجودة، من الرخام الطبيعي إلى الخشب الفاخر، لضمان مظهر أنيق ومتانة طويلة الأمد.</p>";
        } else {
            return "<h4>Exceptional Architectural Design</h4>
<p><strong>{$projectName}</strong> features a modern architectural design that combines elegance with functionality, where the latest technologies and sustainable architectural principles have been applied to create exceptional living spaces.</p>

<h5>Architectural Features:</h5>
<ul>
<li><strong>Sustainable Design:</strong> Use of eco-friendly materials and energy-saving technologies</li>
<li><strong>Large Windows:</strong> Allow natural light entry and provide stunning views</li>
<li><strong>Spacious Balconies:</strong> Perfect outdoor spaces for relaxation and entertainment</li>
<li><strong>High Ceilings:</strong> Create a sense of spaciousness and comfort</li>
<li><strong>Smart Design:</strong> Optimal utilization of spaces and resources</li>
</ul>

<h5>Materials & Finishes:</h5>
<p>The finest materials and high-quality finishes have been used, from natural marble to luxury wood, to ensure an elegant appearance and long-term durability.</p>";
        }
    }

    private function getWhyChooseContent($project, $locale)
    {
        $projectName = $locale == 'ar' ? $project->prj_title_ar : $project->prj_title_en;

        if ($locale == 'ar') {
            return "<h4>لماذا تختار {$projectName}؟</h4>

<h5>الموقع المثالي</h5>
<p>موقع استراتيجي يوفر سهولة الوصول إلى جميع الخدمات والمرافق الأساسية، مع الحفاظ على الهدوء والخصوصية.</p>

<h5>الجودة العالية</h5>
<p>تم تطوير المشروع وفقاً لأعلى معايير الجودة العالمية، مع استخدام أجود المواد والتقنيات المتطورة.</p>

<h5>المرافق المتطورة</h5>
<p>مجموعة شاملة من المرافق الترفيهية والرياضية المصممة لتحسين جودة الحياة اليومية.</p>

<h5>الأمان والخصوصية</h5>
<p>نظام أمان متطور على مدار الساعة مع خصوصية كاملة لجميع السكان.</p>

<h5>قيمة استثمارية عالية</h5>
<p>إمكانية نمو رأس المال عالية مع عوائد إيجار مجزية، مما يجعله استثماراً مثالياً.</p>

<h5>خدمة عملاء متميزة</h5>
<p>فريق خدمة عملاء محترف متاح على مدار الساعة لتلبية جميع احتياجات السكان.</p>";
        } else {
            return "<h4>Why Choose {$projectName}?</h4>

<h5>Perfect Location</h5>
<p>Strategic location providing easy access to all essential services and facilities while maintaining tranquility and privacy.</p>

<h5>High Quality</h5>
<p>The project is developed according to the highest international quality standards, using the finest materials and advanced technologies.</p>

<h5>Advanced Facilities</h5>
<p>Comprehensive range of recreational and sports facilities designed to enhance daily life quality.</p>

<h5>Security & Privacy</h5>
<p>Advanced 24/7 security system with complete privacy for all residents.</p>

<h5>High Investment Value</h5>
<p>High capital growth potential with rewarding rental returns, making it an ideal investment.</p>

<h5>Exceptional Customer Service</h5>
<p>Professional customer service team available 24/7 to meet all residents' needs.</p>";
        }
    }

    private function getLocationContent($project, $locale)
    {
        $areaName = $locale == 'ar' ? $project->area->name_ar : $project->area->name_en;

        if ($locale == 'ar') {
            return "<h4>الموقع والاتصال</h4>
<p>يقع المشروع في <strong>{$areaName}</strong>، أحد أكثر المناطق حيوية وتطوراً في الإمارات العربية المتحدة.</p>

<h5>سهولة الوصول:</h5>
<ul>
<li><strong>الطرق الرئيسية:</strong> 5 دقائق من الطرق السريعة الرئيسية</li>
<li><strong>المطارات:</strong> 20 دقيقة من مطار دبي الدولي</li>
<li><strong>المراكز التجارية:</strong> 10 دقائق من أكبر المراكز التجارية</li>
<li><strong>المستشفيات:</strong> 8 دقائق من أفضل المستشفيات</li>
<li><strong>المدارس:</strong> 5 دقائق من المدارس الدولية المتميزة</li>
</ul>

<h5>المرافق القريبة:</h5>
<p>يحيط بالمشروع مجموعة متنوعة من المرافق والخدمات، من المطاعم الفاخرة إلى المراكز الرياضية والترفيهية، مما يضمن نمط حياة مريح ومتطور.</p>";
        } else {
            return "<h4>Location & Connectivity</h4>
<p>The project is located in <strong>{$areaName}</strong>, one of the most vibrant and developed areas in the United Arab Emirates.</p>

<h5>Easy Access:</h5>
<ul>
<li><strong>Main Roads:</strong> 5 minutes from major highways</li>
<li><strong>Airports:</strong> 20 minutes from Dubai International Airport</li>
<li><strong>Shopping Centers:</strong> 10 minutes from largest shopping centers</li>
<li><strong>Hospitals:</strong> 8 minutes from best hospitals</li>
<li><strong>Schools:</strong> 5 minutes from distinguished international schools</li>
</ul>

<h5>Nearby Facilities:</h5>
<p>The project is surrounded by a variety of facilities and services, from luxury restaurants to sports and entertainment centers, ensuring a comfortable and sophisticated lifestyle.</p>";
        }
    }

    private function getInvestmentContent($project, $locale)
    {
        $projectName = $locale == 'ar' ? $project->prj_title_ar : $project->prj_title_en;

        if ($locale == 'locale') {
            return "<h4>مزايا الاستثمار في {$projectName}</h4>

<h5>نمو رأس المال</h5>
<p>يتميز المشروع بإمكانية نمو رأس المال العالية، حيث تشهد المنطقة نمواً مستمراً في قيم العقارات.</p>

<h5>عوائد الإيجار</h5>
<p>عوائد إيجار مجزية تتراوح بين 6-8% سنوياً، مما يجعله استثماراً مثالياً للمستثمرين.</p>

<h5>الطلب العالي</h5>
<p>طلب مرتفع ومستمر على الوحدات السكنية في المنطقة، مما يضمن سهولة التأجير والبيع.</p>

<h5>الاستقرار الاقتصادي</h5>
<p>الموقع في منطقة اقتصادية مستقرة ومتطورة، مما يضمن أمان الاستثمار على المدى الطويل.</p>

<h5>المرونة في الاستثمار</h5>
<p>خيارات استثمارية متعددة، من الشراء للاستخدام الشخصي إلى الاستثمار للتأجير أو البيع.</p>

<h5>المزايا الضريبية</h5>
<p>مزايا ضريبية جذابة للمستثمرين الأجانب، مما يزيد من جاذبية الاستثمار.</p>";
        } else {
            return "<h4>Investment Benefits in {$projectName}</h4>

<h5>Capital Growth</h5>
<p>The project features high capital growth potential, with the area experiencing continuous growth in real estate values.</p>

<h5>Rental Returns</h5>
<p>Rewarding rental returns ranging from 6-8% annually, making it an ideal investment for investors.</p>

<h5>High Demand</h5>
<p>High and continuous demand for residential units in the area, ensuring easy rental and sale.</p>

<h5>Economic Stability</h5>
<p>Location in a stable and developed economic area, ensuring long-term investment security.</p>

<h5>Investment Flexibility</h5>
<p>Multiple investment options, from purchase for personal use to investment for rental or sale.</p>

<h5>Tax Benefits</h5>
<p>Attractive tax benefits for foreign investors, increasing investment appeal.</p>";
        }
    }
}
