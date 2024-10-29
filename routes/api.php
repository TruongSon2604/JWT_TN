<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BrandsController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\ProductController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::group(['middleware'=>'api','prefix'=>'auth'],function($router)
{
    Route::post('/register',[AuthController::class,'register']);
    Route::post('/login',[AuthController::class,'login']);
    Route::get('/profile',[AuthController::class,'profile']);
    Route::post('/logout',[AuthController::class,'logout']);
    Route::post('/refresh',[AuthController::class,'refresh']);
    Route::post('/changePassword',[AuthController::class,'changePassword']);

});


// Route::group(['middleware' => 'api', 'prefix' => 'auth'], function () {
//     Route::post('/register', [AuthController::class, 'register']);
//     Route::post('/login', [AuthController::class, 'login']);
// });

Route::prefix('brands')->group(function () {
    Route::get('/index',[BrandsController::class,'index']);
    Route::get('/show/{id}',[BrandsController::class,'show']);
    Route::post('/store',[BrandsController::class,'store']);
    Route::post('/update/{id}',[BrandsController::class,'update']);
    Route::delete('/delete/{id}',[BrandsController::class,'delete']);
});

Route::prefix('category')->group(function () {
    Route::get('/index',[CategoryController::class,'index']);
    Route::get('/show/{id}',[CategoryController::class,'show']);
    Route::post('/store',[CategoryController::class,'store']);
    Route::post('/update/{id}',[CategoryController::class,'update']);
    Route::delete('/delete/{id}',[CategoryController::class,'delete']);
});


// Route::prefix('location')->group(function () {
//     Route::get('/index',[LocationController::class,'index'])->middleware('is_admin');
//     Route::post('/store', [LocationController::class, 'store']);

// });

Route::middleware(['auth:api'])->prefix('location')->group(function () {
    Route::get('/index', [LocationController::class, 'index']);;
    Route::post('/store', [LocationController::class, 'store']);
    Route::post('/update/{id}', [LocationController::class, 'update']);
    Route::post('/delete/{id}', [LocationController::class, 'delete']);
});

Route::middleware(['auth:api'])->prefix('product')->group(function () {
    Route::get('/index', [ProductController::class, 'index']);;
    Route::post('/store', [ProductController::class, 'store']);
});
