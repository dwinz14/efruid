<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\OtpVerificationController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Auth\RegisteredUserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {

    // ── Register ──
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);

    // ── Login ──
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    // ── Lupa Password (3 step) ──
    Route::get('forgot-password', [PasswordResetController::class, 'request'])->name('password.request');
    Route::post('forgot-password', [PasswordResetController::class, 'sendOtp'])->name('password.email');

    Route::get('forgot-password/otp', [PasswordResetController::class, 'otpForm'])->name('password.otp');
    Route::post('forgot-password/otp', [PasswordResetController::class, 'verifyOtp'])->name('password.otp.verify');
    Route::post('forgot-password/otp/resend', [PasswordResetController::class, 'resendOtp'])->name('password.otp.resend');

    Route::get('forgot-password/new', [PasswordResetController::class, 'newPasswordForm'])->name('password.new');
    Route::post('forgot-password/new', [PasswordResetController::class, 'updatePassword'])->name('password.update');
});

Route::middleware('auth')->group(function () {

    // ── Verifikasi Email OTP ──
    Route::get('verify-email', [OtpVerificationController::class, 'notice'])->name('verification.notice');
    Route::post('verify-email', [OtpVerificationController::class, 'verify'])->name('verification.verify');
    Route::post('verify-email/resend', [OtpVerificationController::class, 'resend'])->name('verification.resend');

    // ── Logout ──
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});
