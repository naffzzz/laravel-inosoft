<?php

use App\Http\Controllers\TransportationController;
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

Route::prefix('transportation')->group(function () {
    Route::get('/',[TransportationController::class, 'index']);
    Route::get('/{transportationId}',[TransportationController::class, 'show']);
    Route::post('/',[TransportationController::class, 'store']);
    Route::put('/{transportationId}',[TransportationController::class, 'update']);
    Route::patch('/{transportationId}',[TransportationController::class, 'destroy']);
});