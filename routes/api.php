<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;

use App\Models\User;
use App\Http\Controllers\QuoteController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Api\AvatarController;
use App\Http\Controllers\Api\MoodController;
use App\Http\Controllers\Api\MoodTypeController;
use App\Http\Controllers\Api\TagController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Auth\VerifyEmailController;

// Route untuk mendapatkan user yang sedang login (menggunakan Sanctum)
Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

// Group route yang memerlukan autentikasi
Route::middleware('auth:sanctum')->group(function () {
    // Route::apiResource('moods', MoodController::class);
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy']);
    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store']);
});

// Route bebas autentikasi
// Route::apiResource('quotes', QuoteController::class);
Route::post('login',[AuthController::class,'login']);
Route::post('register', [AuthController::class, 'register']);
Route::post('forgot-password', [PasswordResetLinkController::class, 'store']);
Route::post('reset-password', [NewPasswordController::class, 'store']);

// Verifikasi email (setelah register)
Route::get('email/verify/{id}/{hash}', [VerifyEmailController::class, '__invoke'])->name('verification.verify');


Route::middleware('auth:sanctum')->group(function () {
Route::get('/avatars', [AvatarController::class, 'index']);
Route::get('/avatars/{id}', [AvatarController::class, 'show']);
Route::put('/user/avatar', [UserController::class, 'updateAvatar']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('/moods', MoodController::class)->names('api.moods');

    Route::get('/mood-types', [MoodTypeController::class, 'index']);

    Route::get('/tags', [TagController::class, 'index']);
    Route::post('/tags', [TagController::class, 'store']);
});

