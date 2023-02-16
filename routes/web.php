<?php

use App\Http\Controllers\Auth;
use App\Http\Controllers\CompanyListController;
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

Route::get('/', [Auth::class, 'checkIp']);

//Route::get('/dev', function (Request $request) {
//    return view('dev', $request->all());
//});

Route::get('/dev', [SearchController::class, 'index']);

//Route::get('device/{id}', function ($id) {
//    return view('device_info', ['id' => $id]);
//});

Route::get('device/{id}', [ProductController::class, 'index']);

Route::get('/parse', [ParseController::class, 'index']);

Route::get('/company-{name}', [CompanyListController::class, 'index']);
