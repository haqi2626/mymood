<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MoodController;
use App\Http\Controllers\QuoteController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MoodStreakController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/',[DashboardController::class,'index'])->name('app');
Route::resource('quotes', QuoteController::class);
Route::resource('users', UserController::class);
Route::resource('moods', MoodController::class);
Route::resource('mood-streaks', MoodStreakController::class);





