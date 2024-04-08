<?php

use App\Http\Controllers\Api\MusicController;
use App\Http\Controllers\FeminaController;
use App\Http\Controllers\GalleryCategoryController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\ServiceCategoryController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\TeamMemberController;
use App\Http\Controllers\TestimonialController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::get('gallery', [GalleryController::class, 'getAllImages']);
Route::get('gallery_category', [GalleryCategoryController::class, 'getAllCategory']);
Route::get('gallery_category/{id}', [GalleryCategoryController::class, 'getCategory']);
Route::get('members', [TeamMemberController::class, 'getAllMember']);
Route::post('contacts', [SalonController::class,'store']);
Route::post('reservations', [SalonController::class,'reservation']);

Route::get('get_review', [TestimonialController::class, 'getAllReview']);

Route::get('services', [ServiceController::class, 'getAllservices']);
Route::get('service_category', [ServiceCategoryController::class, 'getAllCategory']);
Route::get('service_category/{id}', [ServiceCategoryController::class, 'getCategory']);
Route::get('banners', [FeminaController::class, 'getBannerImages']);



