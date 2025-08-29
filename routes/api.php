<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Apis\HotelController;
use App\Http\Controllers\Apis\PlaceController;
use App\Http\Controllers\Apis\ReviewController;
use App\Http\Controllers\Apis\AmenityController;
use App\Http\Controllers\Apis\FeatureController;
use App\Http\Controllers\Apis\FindstayController;
use App\Http\Controllers\Apis\PromocodeController;
use App\Http\Controllers\Apis\AvilableroomController;
use App\Http\Controllers\Apis\CustomerreviewController;
use App\Http\Controllers\Apis\RamadanofferController;
use App\Http\Controllers\Apis\BooknowController;
use App\Http\Controllers\Apis\HotelinhomeController;
use App\Http\Controllers\Apis\TermController;
use App\Http\Controllers\Apis\HotelinmadinaController;
use App\Http\Controllers\Apis\HotelinmakkahController;
 
use App\Http\Controllers\VisitorLocationController;


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

Route::get('/hotels',[HotelController::class,'index']);
Route::post('/hotels',[HotelController::class,'store']);
Route::post('/hotels/{id}',[HotelController::class,'update']);
Route::delete('/hotels/{id}',[HotelController::class,'destroy']);

Route::get('/availableroom',[AvilableroomController::class,'index']);
Route::post('/availableroom',[AvilableroomController::class,'store']);
Route::post('/availableroom/{id}',[AvilableroomController::class,'update']);
Route::delete('/availableroom/{id}',[AvilableroomController::class,'destroy']);

Route::get('/amenity',[AmenityController::class,'index']);
Route::post('/amenity',[AmenityController::class,'store']);
Route::post('/amenity/{id}',[AmenityController::class,'update']);
Route::delete('/amenity/{id}',[AmenityController::class,'destroy']);

Route::get('/places',[PlaceController::class,'index']);
Route::post('/places',[PlaceController::class,'store']);
Route::post('/places/{id}',[PlaceController::class,'update']);
Route::delete('/places/{id}',[PlaceController::class,'destroy']);

Route::get('/findstays',[FindstayController::class,'index']);
Route::post('/findstays',[FindstayController::class,'store']);
Route::post('/findstays/{id}',[FindstayController::class,'update']);
Route::delete('/findstays/{id}',[FindstayController::class,'destroy']);

Route::get('/features',[FeatureController::class,'index']);
Route::post('/features',[FeatureController::class,'store']);
Route::post('/features/{id}',[FeatureController::class,'update']);
Route::delete('/features/{id}',[FeatureController::class,'destroy']);

Route::get('/promocodes',[PromocodeController::class,'index']);
Route::post('/promocodes',[PromocodeController::class,'store']);
Route::post('/promocodes/{id}',[PromocodeController::class,'update']);
Route::delete('/promocodes/{id}',[PromocodeController::class,'destroy']);

Route::get('/reviews',[ReviewController::class,'index']);
Route::post('/reviews',[ReviewController::class,'store']);
Route::post('/reviews/{id}',[ReviewController::class,'update']);
Route::delete('/reviews/{id}',[ReviewController::class,'destroy']);


Route::get('/customerreviews',[CustomerreviewController::class,'index']);
Route::post('/customerreviews',[CustomerreviewController::class,'store']);
Route::delete('/customerreviews/{id}',[CustomerreviewController::class,'destroy']);

Route::get('/ramadanoffer',[RamadanofferController::class,'index']);
Route::post('/ramadanoffer',[RamadanofferController::class,'store']);
Route::post('/ramadanoffer/{id}',[RamadanofferController::class,'update']);
Route::delete('/ramadanoffer/{id}',[RamadanofferController::class,'destroy']);

Route::get('/booknows',[BooknowController::class,'index']);
Route::post('/booknows',[BooknowController::class,'store']);
Route::delete('/booknows/{id}',[BooknowController::class,'destroy']);

Route::get('/hotelinhome',[HotelinhomeController::class,'index']);
Route::post('/hotelinhome',[HotelinhomeController::class,'store']);
Route::post('/hotelinhome/{id}',[HotelinhomeController::class,'update']);
Route::delete('/hotelinhome/{id}',[HotelinhomeController::class,'destroy']);

Route::get('/terms',[TermController::class,'index']);
Route::post('/terms',[TermController::class,'store']);
Route::post('/terms/{id}',[TermController::class,'update']);
Route::delete('/terms/{id}',[TermController::class,'destroy']);

Route::get('/hotelinmadina',[HotelinmadinaController::class,'index']);
Route::post('/hotelinmadina',[HotelinmadinaController::class,'store']);
Route::post('/hotelinmadina/{id}',[HotelinmadinaController::class,'update']);
Route::delete('/hotelinmadina/{id}',[HotelinmadinaController::class,'destroy']);

Route::get('/hotelinmakkah',[HotelinmakkahController::class,'index']);
Route::post('/hotelinmakkah',[HotelinmakkahController::class,'store']);
Route::post('/hotelinmakkah/{id}',[HotelinmakkahController::class,'update']);
Route::delete('/hotelinmakkah/{id}',[HotelinmakkahController::class,'destroy']);







 



/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Visitor location routes
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