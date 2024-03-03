<?php

use App\Http\Controllers\IngredientcategoryController;
use App\Http\Controllers\IngredientController;
use App\Http\Controllers\IngredientunitController;
use App\Http\Controllers\OutletController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::prefix('/user')
    ->middleware('auth:sanctum')
    ->group(function () {
        Route::get('/list', [UserController::class, 'list']);
        Route::get('/show/{id}', [UserController::class, 'show']);
        Route::delete('/destroy/{id}', [UserController::class, 'destroy']);
    });
Route::prefix('/outlet')
    ->middleware('auth:sanctum')
    ->group(function () {
        Route::get('/list', [OutletController::class, 'list']);
        Route::get('/show/{id}', [OutletController::class, 'show']);
        Route::delete('/destroy/{id}', [OutletController::class, 'destroy']);
    });

Route::prefix('/storage')
    ->middleware('auth:sanctum')
    ->group(function () {
        Route::post('/upload', [OutletController::class, 'upload'])->withoutMiddleware('auth:sanctum');
    });

Route::prefix('/ingredient_unit')
    ->middleware('auth:sanctum')
    ->group(function () {
        Route::get('/list', [IngredientunitController::class, 'list']);
        Route::get('/show/{id}', [IngredientunitController::class, 'show']);
        Route::delete('/destroy/{id}', [IngredientunitController::class, 'destroy']);
    });

Route::prefix('/ingredient_category')
    ->middleware('auth:sanctum')
    ->group(function () {
        Route::get('/list', [IngredientcategoryController::class, 'list']);
        Route::get('/show/{id}', [IngredientcategoryController::class, 'show']);
        Route::delete('/destroy/{id}', [IngredientcategoryController::class, 'destroy']);
    });

Route::prefix('/ingredient')
    ->middleware('auth:sanctum')
    ->group(function () {
        Route::get('/list', [IngredientController::class, 'list']);
        Route::get('/show/{id}', [IngredientController::class, 'show']);
        Route::delete('/destroy/{id}', [IngredientController::class, 'destroy']);
    });
