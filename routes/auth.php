<?php

use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Requests\Auth\AdminLoginRequest;
use Illuminate\Support\Facades\Route;



Route::middleware('guest')->group(function () {
    // Route::get('register', [RegisteredUserController::class, 'create'])
    //     ->name('register');

    // Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    // Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
    //     ->name('password.request');

    // Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
    //     ->name('password.email');

    // Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
    //     ->name('password.reset');

    // Route::post('reset-password', [NewPasswordController::class, 'store'])
    //     ->name('password.store');
});

    Route::get('staff/login', [App\Http\Controllers\Auth\AdminLoginController::class, 'showLoginForm'])
        ->name('staff.login');
    Route::post('staff/login', [App\Http\Controllers\Auth\AdminLoginController::class, 'login'])
        ->name('staff.login.submit');

    Route::post('/staff/logout', [AdminLoginController::class, 'logout'])
        ->name('staff.logout');


Route::prefix('/staff')->group(function() {
    // Route::get('login', [App\Http\Controllers\Staff\Auth\AuthenticatedSessionController::class, 'create'])
    //     ->name('staff.auth.login');
    // Route::post('login', [App\Http\Controllers\Staff\Auth\AuthenticatedSessionController::class, 'store'])
    //     ->name('staff.auth.login');
    // Route::middleware('auth')->group(function () {
        // Route::get('verify-email', EmailVerificationPromptController::class)
        //     ->name('staff.auth.verification.notice');
        // Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        //     ->middleware(['signed', 'throttle:6,1'])
        //     ->name('staff.auth.verification.verify');
        // Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        //     ->middleware('throttle:6,1')
        //     ->name('staff.auth.verification.send');
        // Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
        //     ->name('staff.auth.password.confirm');
        // Route::post('confirm-password', [ConfirmablePasswordController::class, 'store'])
        //     -name('staff.auth.store');
        // Route::put('password', [PasswordController::class, 'update'])
        //     ->name('staff.auth.password.update');
        // Route::get('logout', [AuthenticatedSessionController::class, 'logout'])
        //     ->name('staff.auth.logout');
    //});
});