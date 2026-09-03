<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Models\OtpCode;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Notifications\DatabaseNotification;

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

// Hapus notifikasi yang sudah dibaca lebih dari 90 hari
Schedule::call(function () {
    DatabaseNotification::whereNotNull('read_at')
        ->where('read_at', '<', now()->subDays(90))
        ->delete();
})->dailyAt('01:00')->name('clean-old-notifications')->withoutOverlapping();

// Auto-release klaim IT yang sudah lebih dari 3 hari belum dieksekusi
Schedule::call(function () {
    \App\Models\Permohonan::where('status', \App\Enums\StatusPermohonan::PENDING_IT)
        ->whereNotNull('executor_id')
        ->where('claimed_at', '<', now()->subDays(3))
        ->update(['executor_id' => null, 'claimed_at' => null]);
})->dailyAt('02:00')->name('auto-release-stale-claims')->withoutOverlapping();
