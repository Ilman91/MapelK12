<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\GenreController;
use App\Http\Controllers\Api\AktorController;
use App\Http\Controllers\Api\FilmController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Genre routes
    Route::get('/genre', [GenreController::class, 'index']);
    Route::post('/genre', [GenreController::class, 'store']);
    Route::put('/genre/{id}', [GenreController::class, 'update']);
    Route::delete('/genre/{id}', [GenreController::class, 'destroy']);

    // Aktor routes
    Route::get('/aktor', [AktorController::class, 'index']);
    Route::post('/aktor', [AktorController::class, 'store']);
    Route::put('/aktor/{id}', [AktorController::class, 'update']);
    Route::delete('/aktor/{id}', [AktorController::class, 'destroy']);

    // Film routes
    Route::get('/film', [FilmController::class, 'index']);
    Route::post('/film', [FilmController::class, 'store']);
    Route::put('/film/{id}', [FilmController::class, 'update']);
    Route::delete('/film/{id}', [FilmController::class, 'destroy']);
});