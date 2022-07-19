<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiController;

Route::post('recommendation/', [ApiController::class, 'createRecommendation']);
Route::get('recommendations/', [ApiController::class, 'getAllRecommendations']);
Route::get('recommendation/{id}', [ApiController::class, 'getRecommendation']);
Route::put('recommendation/{id}', [ApiController::class, 'updateRecommendation']);
Route::delete('recommendation/{id}', [ApiController::class, 'removeRecommendation']);
Route::post('recommendation/status/{id}', [ApiController::class, 'updateRecommendationStatus']);

