<?php

use App\Http\Controllers\SaleController;
use App\Http\Controllers\TransportationController;
use App\Http\Controllers\UserController;
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

Route::prefix('user')->group(function () {
    Route::post('/',[UserController::class, 'register']);
    Route::post('/login',[UserController::class, 'login']);
    Route::post('/logout',[UserController::class, 'logout']);
    Route::get('/',[UserController::class, 'index']);
    Route::get('/{userId}',[UserController::class, 'show']);
    Route::put('/{userId}',[UserController::class, 'update']);
    Route::patch('/',[UserController::class, 'destroy']);
});

Route::prefix('sale')->group(function () {
    Route::get('/',[SaleController::class, 'index']);
    Route::get('/{saleId}',[SaleController::class, 'show']);
    Route::post('/{transportationId}',[SaleController::class, 'store']);
    Route::put('/{saleId}',[SaleController::class, 'update']);
    Route::patch('/{saleId}',[SaleController::class, 'destroy']);
});

Route::prefix('transportation')->group(function () {
    Route::get('/',[TransportationController::class, 'index']);
    Route::get('/{transportationId}',[TransportationController::class, 'show']);
    Route::post('/',[TransportationController::class, 'store']);
    Route::put('/add_stock/{transportationId}',[TransportationController::class, 'updateStock']);
    Route::put('/{transportationId}',[TransportationController::class, 'update']);
    Route::patch('/{transportationId}',[TransportationController::class, 'destroy']);
});

