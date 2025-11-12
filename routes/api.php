<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ObjectSentenceController;
use App\Http\Controllers\AuthController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);

    Route::prefix('object-sentences')->group(function () {
        Route::get('/', [ObjectSentenceController::class, 'index']);
        Route::get('/{id}', [ObjectSentenceController::class, 'show']);
        Route::post('/', [ObjectSentenceController::class, 'store']);
        Route::post('/{id}', [ObjectSentenceController::class, 'update']);
        Route::delete('/{id}', [ObjectSentenceController::class, 'destroy']);
    });
});