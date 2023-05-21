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
    Route::post('/',[UserController::class, 'register'])->middleware('jwt');
    Route::post('/login',[UserController::class, 'login']);
    Route::get('/profile',[UserController::class, 'profile'])->middleware('jwt');
    Route::post('/logout',[UserController::class, 'logout'])->middleware('jwt');
    Route::get('/',[UserController::class, 'index'])->middleware('jwt');
    Route::get('/{userId}',[UserController::class, 'show'])->middleware('jwt');
    Route::put('/{userId}',[UserController::class, 'update'])->middleware('jwt');
    Route::patch('/{userId}',[UserController::class, 'destroy'])->middleware('jwt');
});

Route::prefix('sale')->middleware('jwt')->group(function () {
    Route::get('/',[SaleController::class, 'index']);
    Route::get('/{saleId}',[SaleController::class, 'show']);
    Route::post('/{transportationId}',[SaleController::class, 'store']);
    Route::put('/{saleId}',[SaleController::class, 'update']);
    Route::patch('/{saleId}',[SaleController::class, 'destroy']);
});

Route::prefix('transportation')->group(function () {
    Route::get('/',[TransportationController::class, 'index']);
    Route::get('/{transportationId}',[TransportationController::class, 'show']);
    Route::post('/',[TransportationController::class, 'store'])->middleware('jwt');
    Route::put('/add_stock/{transportationId}',[TransportationController::class, 'updateStock'])->middleware('jwt');
    Route::put('/{transportationId}',[TransportationController::class, 'update'])->middleware('jwt');
    Route::patch('/{transportationId}',[TransportationController::class, 'destroy'])->middleware('jwt');
});

