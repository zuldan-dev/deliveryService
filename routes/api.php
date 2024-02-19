<?php

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::namespace('App\Http\Controllers\Api')->group(function () {
    // Auth
    Route::post('/login', 'AuthController@login');
    Route::middleware('auth:api')->post('/logout', 'AuthController@logout');
    // Dishes
    Route::get('/dishes/list', 'DishController@list');
    // Orders
    Route::middleware('auth:api')->put('/order/create', 'OrderController@create');
    Route::middleware('auth:api')->get('/order/status/view', 'OrderController@statusView');
    Route::middleware('auth:api')->put('/order/status/update', 'OrderController@statusUpdate');
    // Stats
    Route::middleware('auth:api')->get('/stats/average/cost_of_orders', 'StatsController@costOfOrders');
    Route::middleware('auth:api')->get('/stats/average/amount_by_drivers', 'StatsController@amountByDrivers');
});
