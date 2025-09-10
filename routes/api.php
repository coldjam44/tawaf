<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Apis\SliderController;
use App\Http\Controllers\Apis\ProjectController;
use App\Http\Controllers\Apis\ProjectDetailController;
use App\Http\Controllers\Apis\ProjectImageController;
use App\Http\Controllers\Apis\ProjectDescriptionController;
use App\Http\Controllers\Apis\ProjectAmenityController;
use App\Http\Controllers\Apis\ProjectContentBlockController;
use App\Http\Controllers\Apis\ProjectContentBlockImageController;
use App\Http\Controllers\Apis\AreaController;
use App\Http\Controllers\Apis\DeveloperController;
use App\Http\Controllers\Apis\RealEstateCompanyController;
use App\Http\Controllers\Apis\AboutUsController;
use App\Http\Controllers\Apis\BlogController;
use App\Http\Controllers\Apis\MessagesController;
use App\Http\Controllers\Apis\AwardController;
use App\Http\Controllers\Apis\ExpertTeamController;
use App\Http\Controllers\Apis\AchievementController;
use App\Http\Controllers\Apis\ServiceController;
use App\Http\Controllers\Apis\ContactUsController;
use App\Http\Controllers\Apis\NewsletterController;
use App\Http\Controllers\VisitorLocationController;
use App\Http\Controllers\Api\PropertyController;
use App\Http\Controllers\BotOffersController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// ========================================
// SLIDER API ROUTES
// ========================================
Route::prefix('sliders')->group(function () {
    Route::get('/', [SliderController::class, 'index']);
    Route::post('/', [SliderController::class, 'store']);
    Route::get('/{id}', [SliderController::class, 'show']);
    Route::put('/{id}', [SliderController::class, 'update']);
    Route::delete('/{id}', [SliderController::class, 'destroy']);
});

// ========================================
// PROJECTS API ROUTES
// ========================================
Route::prefix('projects')->group(function () {
    Route::get('/', [ProjectController::class, 'index']);
    Route::get('/with-details', [ProjectController::class, 'getAllWithDetails']);
    Route::get('/with-details-using-id', [ProjectController::class, 'getProjectWithDetailsById']);
    Route::get('/with-details-using-id/{id}', [ProjectController::class, 'getProjectWithDetailsById']);
    Route::get('/search-with-details', [ProjectController::class, 'searchProjectsWithDetails']);
    Route::post('/', [ProjectController::class, 'store']);
    Route::post('/create-with-all-details', [ProjectController::class, 'createProjectWithAllDetails']);
    Route::get('/{id}', [ProjectController::class, 'show']);
    Route::put('/{id}', [ProjectController::class, 'update']);
    Route::delete('/{id}', [ProjectController::class, 'destroy']);
    
    // Project Details
    Route::get('/{project}/details', [ProjectDetailController::class, 'index']);
    Route::post('/{project}/details', [ProjectDetailController::class, 'store']);
    Route::get('/{project}/details/{detail}', [ProjectDetailController::class, 'show']);
    Route::put('/{project}/details/{detail}', [ProjectDetailController::class, 'update']);
    Route::delete('/{project}/details/{detail}', [ProjectDetailController::class, 'destroy']);
    
    // Project Images
    Route::get('/{project}/images', [ProjectImageController::class, 'index']);
    Route::post('/{project}/images', [ProjectImageController::class, 'store']);
    Route::get('/{project}/images/{image}', [ProjectImageController::class, 'show']);
    Route::put('/{project}/images/{image}', [ProjectImageController::class, 'update']);
    Route::delete('/{project}/images/{image}', [ProjectImageController::class, 'destroy']);
    
    // Project Descriptions
    Route::get('/{project}/descriptions', [ProjectDescriptionController::class, 'index']);
    Route::post('/{project}/descriptions', [ProjectDescriptionController::class, 'store']);
    Route::get('/{project}/descriptions/{description}', [ProjectDescriptionController::class, 'show']);
    Route::put('/{project}/descriptions/{description}', [ProjectDescriptionController::class, 'update']);
    Route::delete('/{project}/descriptions/{description}', [ProjectDescriptionController::class, 'destroy']);
    
    // Project Amenities
    Route::get('/{project}/amenities', [ProjectAmenityController::class, 'index']);
    Route::post('/{project}/amenities', [ProjectAmenityController::class, 'store']);
    Route::put('/{project}/amenities/{amenity}', [ProjectAmenityController::class, 'update']);
    Route::delete('/{project}/amenities/{amenity}', [ProjectAmenityController::class, 'destroy']);
    Route::post('/{project}/amenities/{amenity}/toggle', [ProjectAmenityController::class, 'toggle']);
    Route::post('/{project}/amenities/bulk-update', [ProjectAmenityController::class, 'bulkUpdate']);
    
    // Project Content Blocks
    Route::get('/{project}/content-blocks', [ProjectContentBlockController::class, 'index']);
    Route::post('/{project}/content-blocks', [ProjectContentBlockController::class, 'store']);
    Route::get('/{project}/content-blocks/{block}', [ProjectContentBlockController::class, 'show']);
    Route::put('/{project}/content-blocks/{block}', [ProjectContentBlockController::class, 'update']);
    Route::delete('/{project}/content-blocks/{block}', [ProjectContentBlockController::class, 'destroy']);
    Route::post('/{project}/content-blocks/{block}/toggle', [ProjectContentBlockController::class, 'toggle']);
    Route::post('/{project}/content-blocks/update-order', [ProjectContentBlockController::class, 'updateOrder']);
                Route::get('/{project}/content-blocks/active', [ProjectContentBlockController::class, 'getActive']);
            
            // Project Content Block Images API
            Route::get('/{project}/content-blocks/{block}/images', [ProjectContentBlockImageController::class, 'index']);
            Route::post('/{project}/content-blocks/{block}/images', [ProjectContentBlockImageController::class, 'store']);
            Route::get('/{project}/content-blocks/{block}/images/{image}', [ProjectContentBlockImageController::class, 'show']);
            Route::put('/{project}/content-blocks/{block}/images/{image}', [ProjectContentBlockImageController::class, 'update']);
            Route::delete('/{project}/content-blocks/{block}/images/{image}', [ProjectContentBlockImageController::class, 'destroy']);
            Route::post('/{project}/content-blocks/{block}/images/{image}/toggle', [ProjectContentBlockImageController::class, 'toggle']);
            Route::post('/{project}/content-blocks/{block}/images/update-order', [ProjectContentBlockImageController::class, 'updateOrder']);
});

// ========================================
// AREAS API ROUTES
// ========================================
Route::prefix('areas')->group(function () {
    Route::get('/', [AreaController::class, 'index']);
    Route::post('/', [AreaController::class, 'store']);
    Route::get('/{id}', [AreaController::class, 'show']);
    Route::get('/{id}/with-projects', [AreaController::class, 'getAreaWithProjects']);
    Route::put('/{id}', [AreaController::class, 'update']);
    Route::delete('/{id}', [AreaController::class, 'destroy']);
});

// ========================================
// DEVELOPERS API ROUTES
// ========================================
Route::prefix('developers')->group(function () {
    Route::get('/', [DeveloperController::class, 'index']);
    Route::post('/', [DeveloperController::class, 'store']);
    Route::get('/{id}', [DeveloperController::class, 'show']);
    Route::put('/{id}', [DeveloperController::class, 'update']);
    Route::delete('/{id}', [DeveloperController::class, 'destroy']);
});

// ========================================
// REAL ESTATE COMPANY API ROUTES
// ========================================
Route::prefix('real-estate-company')->group(function () {
    Route::get('/', [RealEstateCompanyController::class, 'index']);
    Route::post('/', [RealEstateCompanyController::class, 'store']);
    Route::get('/{id}', [RealEstateCompanyController::class, 'show']);
    Route::put('/{id}', [RealEstateCompanyController::class, 'update']);
    Route::delete('/{id}', [RealEstateCompanyController::class, 'destroy']);
});

// ========================================
// ABOUT US API ROUTES
// ========================================
Route::prefix('about-us')->group(function () {
    Route::get('/', [AboutUsController::class, 'index']);
    Route::post('/', [AboutUsController::class, 'store']);
    Route::get('/{id}', [AboutUsController::class, 'show']);
    Route::put('/{id}', [AboutUsController::class, 'update']);
    Route::delete('/{id}', [AboutUsController::class, 'destroy']);
    Route::delete('/{sectionId}/images/{imageId}', [AboutUsController::class, 'deleteImage']);
});

// ========================================
// BLOG API ROUTES
// ========================================
Route::prefix('blogsection')->group(function () {
    Route::get('/', [BlogController::class, 'index']);
    Route::post('/', [BlogController::class, 'store']);
    Route::get('/{id}', [BlogController::class, 'show']);
    Route::put('/{id}', [BlogController::class, 'update']);
    Route::delete('/{id}', [BlogController::class, 'destroy']);
    Route::delete('/{blogId}/images/{imageId}', [BlogController::class, 'deleteImage']);
});

// ========================================
// MESSAGES API ROUTES
// ========================================
Route::prefix('messages')->group(function () {
    Route::get('/', [MessagesController::class, 'apiIndex']);
    Route::get('/list', [MessagesController::class, 'apiList']);
    Route::get('/{id}', [MessagesController::class, 'apiShow']);
});

// ========================================
// AWARDS API ROUTES
// ========================================
Route::prefix('awards')->group(function () {
    Route::get('/', [AwardController::class, 'index']);
    Route::post('/', [AwardController::class, 'store']);
    Route::get('/{id}', [AwardController::class, 'show']);
    Route::put('/{id}', [AwardController::class, 'update']);
    Route::delete('/{id}', [AwardController::class, 'destroy']);
});

// ========================================
// EXPERT TEAM API ROUTES
// ========================================
Route::prefix('expert-team')->group(function () {
    Route::get('/', [ExpertTeamController::class, 'index']);
    Route::post('/', [ExpertTeamController::class, 'store']);
    Route::get('/{id}', [ExpertTeamController::class, 'show']);
    Route::put('/{id}', [ExpertTeamController::class, 'update']);
    Route::delete('/{id}', [ExpertTeamController::class, 'destroy']);
});

// ========================================
// ACHIEVEMENTS API ROUTES
// ========================================
Route::prefix('achievements')->group(function () {
    Route::get('/', [AchievementController::class, 'index']);
    Route::post('/', [AchievementController::class, 'store']);
    Route::get('/{id}', [AchievementController::class, 'show']);
    Route::put('/{id}', [AchievementController::class, 'update']);
    Route::delete('/{id}', [AchievementController::class, 'destroy']);
});

// ========================================
// SERVICES API ROUTES
// ========================================
Route::prefix('services')->group(function () {
    Route::get('/', [ServiceController::class, 'index']);
    Route::post('/', [ServiceController::class, 'store']);
    Route::get('/{id}', [ServiceController::class, 'show']);
    Route::put('/{id}', [ServiceController::class, 'update']);
    Route::delete('/{id}', [ServiceController::class, 'destroy']);
});

// ========================================
// CONTACT US API ROUTES
// ========================================
Route::prefix('contact-us')->group(function () {
    Route::get('/', [ContactUsController::class, 'index']);
    Route::post('/', [ContactUsController::class, 'store']);
    Route::get('/{id}', [ContactUsController::class, 'show']);
    Route::put('/{id}', [ContactUsController::class, 'update']);
    Route::delete('/{id}', [ContactUsController::class, 'destroy']);
});

// ========================================
// NEWSLETTER API ROUTES
// ========================================
Route::prefix('newsletter')->group(function () {
    Route::get('/', [NewsletterController::class, 'index']);
    Route::post('/', [NewsletterController::class, 'store']);
    Route::get('/{id}', [NewsletterController::class, 'show']);
    Route::put('/{id}', [NewsletterController::class, 'update']);
    Route::delete('/{id}', [NewsletterController::class, 'destroy']);
});

// ========================================
// VISITOR LOCATION API ROUTES
// ========================================
Route::prefix('visitor')->group(function () {
    // Get current visitor's country
    Route::get('/country', [VisitorLocationController::class, 'getVisitorCountry']);
    
    // Get visitor's full details
    Route::get('/details', [VisitorLocationController::class, 'getVisitorDetails']);
    
    // Get country by specific IP
    Route::get('/country/{ip}', [VisitorLocationController::class, 'getCountryByIP'])
         ->where('ip', '[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}');
});

// Alternative shorter routes
Route::get('/country', [VisitorLocationController::class, 'getVisitorCountry']);
Route::get('/my-location', [VisitorLocationController::class, 'getVisitorDetails']);

// ========================================
// SEARCH API ROUTES
// ========================================
Route::prefix('search')->group(function () {
    // Search projects
    Route::get('/projects', [ProjectController::class, 'search']);
    
    // Search developers
    Route::get('/developers', [DeveloperController::class, 'search']);
    
    // Search areas
    Route::get('/areas', [AreaController::class, 'search']);
    
    // Search blogs
    Route::get('/blogsection', [BlogController::class, 'search']);
    
    // Search real estate companies
    Route::get('/real-estate-company', [RealEstateCompanyController::class, 'search']);
});

// ========================================
// DASHBOARD STATISTICS API ROUTES
// ========================================
Route::prefix('dashboard')->group(function () {
    Route::get('/stats', function () {
        return response()->json([
            'total_projects' => \App\Models\Project::count(),
            'total_developers' => \App\Models\Developer::count(),
            'total_areas' => \App\Models\Area::count(),
            'total_blogs' => \App\Models\Blog::count(),
            'total_newsletters' => \App\Models\Newsletter::count(),
            'total_contact_requests' => \App\Models\ContactUs::count(),
        ]);
    });
});

// Property API Routes
Route::prefix('properties')->group(function () {
    Route::get('/', [PropertyController::class, 'index']);
    Route::get('/all-details', [PropertyController::class, 'getAllWithDetails']);
    Route::get('/all-details/{id}', [PropertyController::class, 'getAllWithDetailsbyid']);
    Route::get('/search', [PropertyController::class, 'search']);
    Route::post('/', [PropertyController::class, 'store']);
    Route::get('/{property}', [PropertyController::class, 'show']);
    Route::put('/{property}', [PropertyController::class, 'update']);
    Route::delete('/{property}', [PropertyController::class, 'destroy']);
    
    // Additional endpoints
    Route::get('/project/{project_id}/employees', [PropertyController::class, 'getProjectEmployees']);
    Route::get('/project/{project_id}/properties', [PropertyController::class, 'getPropertiesByProject']);
    Route::get('/projects/list', [PropertyController::class, 'getProjects']);
    Route::get('/payment-plans/list', [PropertyController::class, 'getPaymentPlans']);
});

// Project basic details endpoint (moved outside properties group)
Route::get('/project/{project_id}/basic-details', [PropertyController::class, 'getProjectBasicDetails']);

// Get all projects basic details
Route::get('/project/basic-details', [PropertyController::class, 'getAllProjectsBasicDetails']);

// ========================================
// BOT OFFERS API ROUTES
// ========================================
Route::prefix('bot')->group(function () {
    // Get all projects that are in the bot offer list
    Route::get('/offers', [BotOffersController::class, 'getBotProjects']);
});

// ========================================
// CONTACT MESSAGES API ROUTES
// ========================================
Route::prefix('contact-messages')->group(function () {
    Route::post('/', [App\Http\Controllers\Api\ContactMessageController::class, 'store']);
    Route::get('/', [App\Http\Controllers\Api\ContactMessageController::class, 'index']);
    Route::get('/{id}', [App\Http\Controllers\Api\ContactMessageController::class, 'show']);
});