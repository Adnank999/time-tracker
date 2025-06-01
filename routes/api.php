<?php

use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\TimeLogController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;

Route::post('/register', [RegisteredUserController::class, 'store'])
    ->middleware('guest')
    ->name('register');

Route::post('/login', [AuthenticatedSessionController::class, 'store'])
    ->middleware(['guest'])
    ->name('login');


Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
    ->middleware('guest')
    ->name('password.email');

Route::post('/reset-password', [NewPasswordController::class, 'store'])
    ->middleware('guest')
    ->name('password.store');

Route::get('/verify-email/{id}/{hash}', VerifyEmailController::class)
    ->middleware(['auth', 'signed', 'throttle:6,1'])
    ->name('verification.verify');

Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
    ->middleware(['auth', 'throttle:6,1'])
    ->name('verification.send');

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth:sanctum') 
    ->name('logout');

    
Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('timelogs')->group(function () {
        Route::get('/', [TimeLogController::class, 'index']);
        Route::post('/', [TimeLogController::class, 'store']);
        Route::post('start', [TimeLogController::class, 'start']);
        Route::post('stop/{id}', [TimeLogController::class, 'stop']);
        Route::put('{log}', [TimeLogController::class, 'update']);
        
    });

    Route::get('/report', [ReportController::class, 'summary']);
});
