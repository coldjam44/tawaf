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

Route::group(
    [
        'prefix' => LaravelLocalization::setLocale(),
        'middleware' => [ 'localeSessionRedirect', 'localizationRedirect', 'localeViewPath' ]
    ], function(){

        Auth::routes();

        Route::group(['middleware' => 'guest'],function(){
            Route::get('/', function () {
                return view('auth.login');
            });
        });
        Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

        Route::resource('places',PlaceController::class);
        Route::resource('hotels',HotelController::class);
        Route::resource('customerreviews',ReviewController::class);
        Route::resource('ramadanoffers',RamadanofferController::class);
        Route::resource('amenitys',AmenityController::class);
        Route::resource('promocodes',PromocodeController::class);
        Route::resource('avilablerooms',AvilableroomController::class);
        Route::resource('features',FeatureController::class);
        Route::resource('reviews',CustomerreviewController::class);
              Route::resource('booknows',BooknowController::class);
             // Route::resource('hotelinhomes',HotelinhomeController::class);
              Route::resource('terms',TermController::class);
         Route::resource('hotelinmadinas',HotelinmadinaController::class);
        Route::resource('hotelinmakkahs',HotelinmakkahController::class);




});



