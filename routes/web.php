<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\IngredientcategoryController;
use App\Http\Controllers\IngredientController;
use App\Http\Controllers\IngredientunitController;
use App\Http\Controllers\OutletController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified',])->group(function () {
});

Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, '_login'])->name('_login');

Route::prefix('/')
    ->middleware('auth')
    ->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    });

Route::prefix('/outlet')
    ->middleware('auth')
    ->group(function () {
        Route::get('/', [OutletController::class, 'index'])->name('outlet');
        Route::get('/create', [OutletController::class, 'create'])->name('outlet.create');
        Route::get('/edit/{id}', [OutletController::class, 'edit'])->name('outlet.edit');
        Route::post('/store', [OutletController::class, 'store'])->name('outlet.store');
        Route::post('/update/{id}', [OutletController::class, 'update'])->name('outlet.update');
    });

Route::prefix('/user')
    ->middleware('auth')
    ->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('user');
        Route::get('/edit/{id}', [UserController::class, 'edit'])->name('user.edit');
        Route::get('/create', [UserController::class, 'create'])->name('user.create');
        Route::post('/store', [UserController::class, 'store'])->name('user.store');
        Route::post('/update/{id}', [UserController::class, 'update'])->name('user.update');
    });

Route::prefix('/ingredient_unit')
    ->middleware('auth')
    ->group(function () {
        Route::get('/', [IngredientunitController::class, 'index'])->name('ingredient_unit');
        Route::get('/create', [IngredientunitController::class, 'create'])->name('ingredient_unit.create');
        Route::get('/edit/{id}', [IngredientunitController::class, 'edit'])->name('ingredient_unit.edit');
        Route::post('/store', [IngredientunitController::class, 'store'])->name('ingredient_unit.store');
        Route::post('/update/{id}', [IngredientunitController::class, 'update'])->name('ingredient_unit.update');
    });

Route::prefix('/ingredient_category')
    ->middleware('auth')
    ->group(function () {
        Route::get('/', [IngredientcategoryController::class, 'index'])->name('ingredient_category');
        Route::get('/create', [IngredientcategoryController::class, 'create'])->name('ingredient_category.create');
        Route::get('/edit/{id}', [IngredientcategoryController::class, 'edit'])->name('ingredient_category.edit');
        Route::post('/store', [IngredientcategoryController::class, 'store'])->name('ingredient_category.store');
        Route::post('/update/{id}', [IngredientcategoryController::class, 'update'])->name('ingredient_category.update');
    });

Route::prefix('/ingredient')
    ->middleware('auth')
    ->group(function () {
        Route::get('/', [IngredientController::class, 'index'])->name('ingredient');
        Route::get('/create', [IngredientController::class, 'create'])->name('ingredient.create');
        Route::get('/edit/{id}', [IngredientController::class, 'edit'])->name('ingredient.edit');
        Route::post('/store', [IngredientController::class, 'store'])->name('ingredient.store');
        Route::post('/update/{id}', [IngredientController::class, 'update'])->name('ingredient.update');
    });
