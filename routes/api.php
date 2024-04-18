<?php

use App\Http\Controllers\ActivityLogsController;
use App\Http\Controllers\DevicesController;
use App\Http\Controllers\UsersController;
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

//pridet access token?
Route::post('registerDevice', [DevicesController::class, 'registerDevice']);
Route::post('movementDetected', [ActivityLogsController::class, 'movementDetected']);
Route::post('decrypt', [UsersController::class, 'decrypt']);

Route::group(['middleware' => ['auth']], function () {
    Route::get('getStatistics/{device_name}', [ActivityLogsController::class, 'getStatistics']);
});
