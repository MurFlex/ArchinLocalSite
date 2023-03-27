<?php

use App\Http\Controllers\Auth;
use App\Http\Controllers\CompanyCategoriesController;
use App\Http\Controllers\CompanyListController;
use App\Http\Controllers\FilesToDbTransition;
use App\Http\Controllers\ParseController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SearchController;

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

// todo group by controllers using middlewares

Route::get('/', [Auth::class, 'checkIp']);

// Search controller routes

Route::get('/search', [SearchController::class, 'index']);

Route::post('/api/delete', [SearchController::class, 'banCompany']);

Route::post('/api/return', [SearchController::class, 'unbanCompany']);

Route::post('/api/rename/', [SearchController::class, 'renameCompany']);

// Product controller routes

Route::get('/device/{id}', [ProductController::class, 'index']);

// Parse controller routes

Route::get('/temp', [ParseController::class, 'temp']);

Route::get('/parse', [ParseController::class, 'index']);

Route::get('/updateStorage', [ParseController::class, 'updateStorage']);

Route::post('/api/device/{id}', [ParseController::class, 'insertDevice']);

// Company list controller routes

Route::get('/company/{id}', [CompanyListController::class, 'index']);

// Company categories controller routes

Route::get('/company/{id}/{category_id}', [CompanyCategoriesController::class, 'index']);

// Transition routes

Route::get('/trans', [FilesToDbTransition::class, 'index']);
