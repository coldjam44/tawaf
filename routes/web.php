<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HotelController;
use App\Http\Controllers\PlaceController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\AmenityController;
use App\Http\Controllers\FeatureController;
use App\Http\Controllers\FindstayController;
use App\Http\Controllers\PromocodeController;
use App\Http\Controllers\AvilableroomController;
use App\Http\Controllers\CustomerreviewController;
use App\Http\Controllers\RamadanofferController;
use App\Http\Controllers\BooknowController;
use App\Http\Controllers\HotelinhomeController;
use App\Http\Controllers\TermController;
use App\Http\Controllers\HotelinmadinaController;
use App\Http\Controllers\HotelinmakkahController;
use App\Http\Controllers\SliderController;
use App\Http\Controllers\DeveloperController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectDetailController;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\ProjectImageController;
use App\Http\Controllers\ProjectDescriptionController;
use App\Http\Controllers\ProjectAmenityController;
use App\Http\Controllers\ProjectContentBlockController;
use App\Http\Controllers\ProjectContentBlockImageController;
use App\Http\Controllers\AboutUsController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\PropertyController;


use App\Models\customerreview;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


use Torann\GeoIP\Facades\GeoIP;
use Illuminate\Http\Request;

Route::get('/visitor-location', function (Request $request) {
    $testIp = '2.58.0.1'; // أي IP خارجي معروف (مثلاً فرنسا)
    $location = GeoIP::getLocation($testIp);
    return response()->json($location);
});



Route::group(
    [
        'prefix' => LaravelLocalization::setLocale(),
        'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath']
    ],
    function () {

        Auth::routes();

        Route::group(['middleware' => 'guest'], function () {
            Route::get('/', function () {
                return view('auth.login');
            });
        });
        Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

        Route::resource('places', PlaceController::class);
        Route::resource('hotels', HotelController::class);
        Route::resource('customerreviews', ReviewController::class);
        Route::resource('ramadanoffers', RamadanofferController::class);
        Route::resource('amenitys', AmenityController::class);
        Route::resource('promocodes', PromocodeController::class);
        Route::resource('avilablerooms', AvilableroomController::class);
        Route::resource('features', FeatureController::class);
        Route::resource('reviews', CustomerreviewController::class);
        Route::resource('booknows', BooknowController::class);
        // Route::resource('hotelinhomes',HotelinhomeController::class);
        Route::resource('terms', TermController::class);
        Route::resource('hotelinmadinas', HotelinmadinaController::class);

        Route::resource('hotelinmakkahs', HotelinmakkahController::class);


        Route::resource('sliders', SliderController::class);
        Route::resource('developers', DeveloperController::class);
        
        Route::resource('projects', ProjectController::class);


        // Project Details Routes
        Route::get('projects/{project}/details', [ProjectDetailController::class, 'index'])->name('project-details.index');
        Route::get('projects/{project}/details/create', [ProjectDetailController::class, 'create'])->name('project-details.create');
        Route::post('projects/{project}/details', [ProjectDetailController::class, 'store'])->name('project-details.store');
        Route::get('projects/{project}/details/{detail}/edit', [ProjectDetailController::class, 'edit'])->name('project-details.edit');
        Route::put('projects/{project}/details/{detail}', [ProjectDetailController::class, 'update'])->name('project-details.update');
        Route::delete('projects/{project}/details/{detail}', [ProjectDetailController::class, 'destroy'])->name('project-details.destroy');
        Route::get('projects/{project}/details/show', [ProjectDetailController::class, 'show'])->name('project-details.show');

        // Areas Routes
        Route::resource('areas', AreaController::class);

        // Project Images Routes
        Route::get('projects/{project}/images', [ProjectImageController::class, 'index'])->name('project-images.index');
        Route::get('projects/{project}/images/create', [ProjectImageController::class, 'create'])->name('project-images.create');
        Route::post('projects/{project}/images', [ProjectImageController::class, 'store'])->name('project-images.store');
        Route::get('projects/{project}/images/{image}/edit', [ProjectImageController::class, 'edit'])->name('project-images.edit');
        Route::put('projects/{project}/images/{image}', [ProjectImageController::class, 'update'])->name('project-images.update');
        Route::delete('projects/{project}/images/{image}', [ProjectImageController::class, 'destroy'])->name('project-images.destroy');
        Route::get('projects/{project}/images/show', [ProjectImageController::class, 'show'])->name('project-images.show');

        // Project Descriptions Routes
        Route::get('projects/{project}/descriptions', [ProjectDescriptionController::class, 'index'])->name('project-descriptions.index');
        Route::get('projects/{project}/descriptions/create', [ProjectDescriptionController::class, 'create'])->name('project-descriptions.create');
        Route::post('projects/{project}/descriptions', [ProjectDescriptionController::class, 'store'])->name('project-descriptions.store');
        Route::get('projects/{project}/descriptions/{description}/edit', [ProjectDescriptionController::class, 'edit'])->name('project-descriptions.edit');
        Route::put('projects/{project}/descriptions/{description}', [ProjectDescriptionController::class, 'update'])->name('project-descriptions.update');
        Route::delete('projects/{project}/descriptions/{description}', [ProjectDescriptionController::class, 'destroy'])->name('project-descriptions.destroy');
        Route::get('projects/{project}/descriptions/show', [ProjectDescriptionController::class, 'show'])->name('project-descriptions.show');

        // Project Amenities Routes
        Route::get('projects/{project}/amenities', [ProjectAmenityController::class, 'index'])->name('project-amenities.index');
        Route::post('projects/{project}/amenities', [ProjectAmenityController::class, 'store'])->name('project-amenities.store');
        Route::put('projects/{project}/amenities/{amenity}', [ProjectAmenityController::class, 'update'])->name('project-amenities.update');
        Route::delete('projects/{project}/amenities/{amenity}', [ProjectAmenityController::class, 'destroy'])->name('project-amenities.destroy');
        Route::post('projects/{project}/amenities/{amenity}/toggle', [ProjectAmenityController::class, 'toggle'])->name('project-amenities.toggle');
        Route::post('projects/{project}/amenities/bulk-update', [ProjectAmenityController::class, 'bulkUpdate'])->name('project-amenities.bulk-update');
        Route::get('projects/{project}/amenities/show', [ProjectAmenityController::class, 'show'])->name('project-amenities.show');

        // Project Content Blocks Routes
        Route::get('projects/{project}/content-blocks', [ProjectContentBlockController::class, 'index'])->name('project-content-blocks.index');
        Route::get('projects/{project}/content-blocks/create', [ProjectContentBlockController::class, 'create'])->name('project-content-blocks.create');
        Route::post('projects/{project}/content-blocks', [ProjectContentBlockController::class, 'store'])->name('project-content-blocks.store');
        Route::get('projects/{project}/content-blocks/{block}/edit', [ProjectContentBlockController::class, 'edit'])->name('project-content-blocks.edit');
        Route::put('projects/{project}/content-blocks/{block}', [ProjectContentBlockController::class, 'update'])->name('project-content-blocks.update');
        Route::delete('projects/{project}/content-blocks/{block}', [ProjectContentBlockController::class, 'destroy'])->name('project-content-blocks.destroy');
        Route::post('projects/{project}/content-blocks/{block}/toggle', [ProjectContentBlockController::class, 'toggle'])->name('project-content-blocks.toggle');
        Route::post('projects/{project}/content-blocks/update-order', [ProjectContentBlockController::class, 'updateOrder'])->name('project-content-blocks.update-order');
        // Project Content Block Images Routes
        Route::post('projects/{project}/content-blocks/{block}/images', [ProjectContentBlockImageController::class, 'store'])->name('project-content-blocks.images.store');
        Route::delete('projects/{project}/content-blocks/{block}/images/{image}', [ProjectContentBlockImageController::class, 'destroy'])->name('project-content-blocks.images.destroy');
        Route::post('projects/{project}/content-blocks/{block}/images/{image}/toggle', [ProjectContentBlockImageController::class, 'toggle'])->name('project-content-blocks.images.toggle');
        Route::post('projects/{project}/content-blocks/{block}/images/update-order', [ProjectContentBlockImageController::class, 'updateOrder'])->name('project-content-blocks.images.update-order');

        // About Us Routes
        Route::get('about-us', [AboutUsController::class, 'index'])->name('about-us.index');
        Route::get('about-us/create', [AboutUsController::class, 'create'])->name('about-us.create');
        Route::post('about-us', [AboutUsController::class, 'store'])->name('about-us.store');
        Route::get('about-us/{id}/edit', [AboutUsController::class, 'edit'])->name('about-us.edit');
        Route::put('about-us/{id}', [AboutUsController::class, 'update'])->name('about-us.update');
        Route::delete('about-us/{id}', [AboutUsController::class, 'destroy'])->name('about-us.destroy');
        Route::get('about-us/show', [AboutUsController::class, 'show'])->name('about-us.show');
        Route::delete('about-us/{sectionId}/images/{imageId}', [AboutUsController::class, 'deleteImage'])->name('about-us.delete-image');

        // Blog Routes
        Route::resource('blogs', BlogController::class);
        Route::delete('blogs/{blogId}/images/{imageId}', [BlogController::class, 'deleteImage'])->name('blogs.delete-image');

        // Awards Routes
        Route::get('awards', [App\Http\Controllers\AwardController::class, 'index'])->name('awards.index');
        Route::get('awards/create', [App\Http\Controllers\AwardController::class, 'create'])->name('awards.create');
        Route::post('awards', [App\Http\Controllers\AwardController::class, 'store'])->name('awards.store');
        Route::get('awards/{id}/edit', [App\Http\Controllers\AwardController::class, 'edit'])->name('awards.edit');
        Route::put('awards/{id}', [App\Http\Controllers\AwardController::class, 'update'])->name('awards.update');
        Route::delete('awards/{id}', [App\Http\Controllers\AwardController::class, 'destroy'])->name('awards.destroy');

        // Expert Team Routes
        Route::get('expert-team', [App\Http\Controllers\ExpertTeamController::class, 'index'])->name('expert-team.index');
        Route::get('expert-team/{id}/edit', [App\Http\Controllers\ExpertTeamController::class, 'edit'])->name('expert-team.edit');
        Route::put('expert-team/{id}', [App\Http\Controllers\ExpertTeamController::class, 'update'])->name('expert-team.update');
        Route::post('expert-team', [App\Http\Controllers\ExpertTeamController::class, 'store'])->name('expert-team.store');
        Route::delete('expert-team/{id}', [App\Http\Controllers\ExpertTeamController::class, 'destroy'])->name('expert-team.destroy');

        // Achievements Routes
        Route::get('achievements', [App\Http\Controllers\AchievementController::class, 'index'])->name('achievements.index');
        Route::get('achievements/{id}/edit', [App\Http\Controllers\AchievementController::class, 'edit'])->name('achievements.edit');
        Route::put('achievements/{id}', [App\Http\Controllers\AchievementController::class, 'update'])->name('achievements.update');
        Route::post('achievements', [App\Http\Controllers\AchievementController::class, 'store'])->name('achievements.store');
        Route::delete('achievements/{id}', [App\Http\Controllers\AchievementController::class, 'destroy'])->name('achievements.destroy');

        // Services Routes
        Route::get('services', [App\Http\Controllers\ServiceController::class, 'index'])->name('services.index');
        Route::get('services/{id}/edit', [App\Http\Controllers\ServiceController::class, 'edit'])->name('services.edit');
        Route::put('services/{id}', [App\Http\Controllers\ServiceController::class, 'update'])->name('services.update');
        Route::post('services', [App\Http\Controllers\ServiceController::class, 'store'])->name('services.store');
        Route::delete('services/{id}', [App\Http\Controllers\ServiceController::class, 'destroy'])->name('services.destroy');

        Route::get('real-estate-company', [App\Http\Controllers\RealEstateCompanyController::class, 'index'])->name('real-estate-company.index');
        Route::get('real-estate-company/{id}/edit', [App\Http\Controllers\RealEstateCompanyController::class, 'edit'])->name('real-estate-company.edit');
        Route::put('real-estate-company/{id}', [App\Http\Controllers\RealEstateCompanyController::class, 'update'])->name('real-estate-company.update');
        Route::post('real-estate-company', [App\Http\Controllers\RealEstateCompanyController::class, 'store'])->name('real-estate-company.store');
        Route::delete('real-estate-company/{id}', [App\Http\Controllers\RealEstateCompanyController::class, 'destroy'])->name('real-estate-company.destroy');

        // Contact Us Routes
        Route::get('contact-us', [App\Http\Controllers\ContactUsController::class, 'index'])->name('contact-us.index');
        Route::post('contact-us', [App\Http\Controllers\ContactUsController::class, 'store'])->name('contact-us.store');
        Route::get('contact-us/{id}/edit', [App\Http\Controllers\ContactUsController::class, 'edit'])->name('contact-us.edit');
        Route::put('contact-us/{id}', [App\Http\Controllers\ContactUsController::class, 'update'])->name('contact-us.update');
        Route::delete('contact-us/{id}', [App\Http\Controllers\ContactUsController::class, 'destroy'])->name('contact-us.destroy');
        Route::get('contact-us/{id}', [App\Http\Controllers\ContactUsController::class, 'show'])->name('contact-us.show');

        // Newsletter Routes
        Route::resource('newsletter', NewsletterController::class);

        // Properties Routes
Route::resource('properties', PropertyController::class);

Route::post('properties/select-project', [PropertyController::class, 'selectProject'])->name('properties.select-project');
Route::get('properties/get-project-location/{projectId}', [PropertyController::class, 'getProjectLocation'])->name('properties.get-project-location');
    }
);
