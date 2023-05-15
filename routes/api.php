<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\OrderController;
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

Route::post('/auth/register', [AuthController::class, 'createUser']);
Route::post('/auth/login', [AuthController::class, 'loginUser']);
Route::group(['prefix' => 'order'], function () {
    Route::get('/', [OrderController::class, 'index'])->middleware('auth:sanctum');
    Route::post('/create', [OrderController::class, 'create'])->middleware('auth:sanctum');
    Route::get('/order/{id}', [OrderController::class, 'store'])->middleware('auth:sanctum');
    Route::delete('/destroy/{id}', [OrderController::class, 'destroy'])->middleware('auth:sanctum');

});

