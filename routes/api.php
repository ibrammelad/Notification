<?php

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
    ////////////////// show user ///////////////////////////////
    Route::controller(\App\Http\Controllers\UserController::class)->group(function () {
        Route::get('/users/{user}', 'getUser');
    });
    ////////////////// show captain ///////////////////////////////
    Route::controller(\App\Http\Controllers\CaptainController::class)->group(function () {
            Route::get('/captains/{captain}', 'getCaptain');
        });
    ////////////////// show orders ///////////////////////////////
    Route::controller(\App\Http\Controllers\OrderController::class)->group(function () {
            Route::get('/orders', 'showOrders');
            Route::post('/orders', 'createOrder');
            Route::post('/orderCancel/{order}', 'orderCancel');
            Route::get('/distance', 'distance1');
        });
