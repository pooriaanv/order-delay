<?php

use App\Http\Controllers\Agent\Vendor\VendorController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\User\Order\OrderDelayController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::group(['namespace' => 'Auth', 'prefix' => 'auth'], function () {
    Route::post('login', [AuthController::class, 'login']);
});

Route::group(['namespace' => 'Order', 'prefix' => 'order'], function () {
    Route::post('delay', [OrderDelayController::class, 'reportDelay']);
});

Route::group(['namespace' => 'Agent', 'prefix' => 'agent', 'middleware' => ['auth:user']], function () {
    Route::group(['prefix' => 'order'], function () {
        Route::get('delay/pick', [\App\Http\Controllers\Agent\Order\OrderDelayController::class, 'pickReport']);
    });
    Route::group(['prefix' => 'vendor'], function () {
        Route::get('statistics/delay', [VendorController::class, 'delayStatistics']);
    });
});
