<?php

// use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Api\AvatarController;
use App\Http\Controllers\Api\MoodController;
use App\Http\Controllers\Api\MoodStreakController;
use App\Http\Controllers\Api\MoodTypeController;
use App\Http\Controllers\Api\PublicMoodController;
use App\Http\Controllers\Api\QuoteController;
use App\Http\Controllers\Api\TagController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Auth\VerifyEmailController;


Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy']);
    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store']);
});

Route::post('login',[AuthController::class,'login']);
Route::post('register', [AuthController::class, 'register']);
Route::post('forgot-password', [PasswordResetLinkController::class, 'store']);
Route::post('reset-password', [NewPasswordController::class, 'store']);


Route::get('email/verify/{id}/{hash}', [VerifyEmailController::class, '__invoke'])->name('verification.verify');


Route::middleware('auth:sanctum')->group(function () {
    // List avatar
    Route::get('/avatars', [AvatarController::class, 'index']);
    Route::get('/avatars/{id}', [AvatarController::class, 'show']);

    // Ambil profil user yang sedang login
    Route::get('/user', [UserController::class, 'getProfile']); // <-- Tambahkan ini

    // Update avatar user
    Route::put('/user', [UserController::class, 'updateProfile']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('/moods', MoodController::class)->names('api.moods');

    Route::get('/mood-types', [MoodTypeController::class, 'index']);

    Route::get('/tags', [TagController::class, 'index']);
    Route::post('/tags', [TagController::class, 'store']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('mood-streaks', MoodStreakController::class);

    // Tambahkan ini:
    Route::get('/mood-streak', [MoodStreakController::class, 'getStreak']);
});


Route::apiResource('/public-moods', PublicMoodController::class);

Route::get('/quotes', [QuoteController::class, 'index']);
Route::get('/quotes/random', [QuoteController::class, 'random']);
