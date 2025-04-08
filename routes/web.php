<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JobController;
use App\Http\Controllers\ModeratorController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


// Job Seeker Routes
Route::get('/', [JobController::class, 'index'])->name('jobs.index');
Route::get('/jobs/create', [JobController::class, 'showCreateForm'])->name('jobs.create');
Route::post('/jobs', [JobController::class, 'storeJob'])->name('jobs.store');

// Moderator Routes
Route::prefix('moderator')->group(function () {
    Route::get('/', [ModeratorController::class, 'index'])->name('moderator.index');
    Route::get('/pending', [ModeratorController::class, 'showPendingJobs'])->name('moderator.pending');
    Route::post('/approve/{job}', [ModeratorController::class, 'approveJob'])->name('moderator.approve');
    Route::post('/spam/{job}', [ModeratorController::class, 'markAsSpam'])->name('moderator.spam');
});