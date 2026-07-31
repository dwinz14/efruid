<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Models\OtpCode;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Hapus OTP expired setiap 30 menit
Schedule::call(function () {
    OtpCode::where('expires_at', '<', now())
        ->orWhereNotNull('used_at')
        ->where('created_at', '<', now()->subDay())
        ->delete();
})->everyThirtyMinutes()->name('clean-expired-otp')->withoutOverlapping();
