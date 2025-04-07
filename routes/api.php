<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JobController;
use App\Http\Controllers\ModeratorController;


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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('jobs')->group(function () {
    Route::get('/', [JobController::class, 'index']);
    Route::post('/', [JobController::class, 'store']);
});

Route::prefix('moderator')->group(function () {
    Route::post('/jobs/{job}/approve', [ModeratorController::class, 'approve'])
        ->name('moderator.approve');
    Route::post('/jobs/{job}/spam', [ModeratorController::class, 'markAsSpam'])
        ->name('moderator.spam');
});