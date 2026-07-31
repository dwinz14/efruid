<?php

namespace App\Services;

use App\Models\OtpCode;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\OtpMail;

class OtpService
{
    // Konfigurasi konstanta
    const EXPIRE_MINUTES   = 10;
    const MAX_ATTEMPTS     = 3;
    const RESEND_COOLDOWN  = 60;  // detik
    const MAX_RESEND_PER_HOUR = 3;

    /**
     * Generate OTP baru, invalidate OTP lama, kirim via email (queue).
     * Return false jika cooldown belum habis.
     */
    public function send(User $user, string $purpose): bool
    {
        // Cek cooldown: OTP terakhir dibuat < 60 detik yang lalu
        $last = OtpCode::where('user_id', $user->id)
            ->where('purpose', $purpose)
            ->latest()
            ->first();

        if ($last && $last->created_at->diffInSeconds(now()) < self::RESEND_COOLDOWN) {
            return false; // masih cooldown
        }

        // Cek max resend per jam
        $countThisHour = OtpCode::where('user_id', $user->id)
            ->where('purpose', $purpose)
            ->where('created_at', '>=', now()->subHour())
            ->count();

        if ($countThisHour >= self::MAX_RESEND_PER_HOUR) {
            return false;
        }

        // Invalidate semua OTP lama untuk purpose ini
        OtpCode::where('user_id', $user->id)
            ->where('purpose', $purpose)
            ->whereNull('used_at')
            ->delete();

        // Generate 6 digit OTP
        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        OtpCode::create([
            'user_id'      => $user->id,
            'purpose'      => $purpose,
            'code_hash'    => Hash::make($code),
            'attempt_count' => 0,
            'expires_at'   => now()->addMinutes(self::EXPIRE_MINUTES),
        ]);

        // Kirim via queue
        Mail::to($user->email)->queue(new OtpMail($user, $code, $purpose));

        return true;
    }

    /**
     * Verifikasi OTP. Return string status:
     * 'valid' | 'invalid' | 'expired' | 'max_attempt' | 'not_found'
     */
    public function verify(User $user, string $purpose, string $code): string
    {
        $otp = OtpCode::where('user_id', $user->id)
            ->where('purpose', $purpose)
            ->whereNull('used_at')
            ->latest()
            ->first();

        if (! $otp) {
            return 'not_found';
        }

        if ($otp->isExpired()) {
            return 'expired';
        }

        if ($otp->isMaxAttempt()) {
            return 'max_attempt';
        }

        if (! Hash::check($code, $otp->code_hash)) {
            $otp->increment('attempt_count');
            return 'invalid';
        }

        // OTP valid — tandai sebagai used
        $otp->update(['used_at' => now()]);

        return 'valid';
    }

    /**
     * Cek apakah masih dalam cooldown resend.
     * Return sisa detik cooldown, atau 0 jika sudah bisa resend.
     */
    public function resendCooldownSeconds(User $user, string $purpose): int
    {
        $last = OtpCode::where('user_id', $user->id)
            ->where('purpose', $purpose)
            ->latest()
            ->first();

        if (! $last) return 0;

        $elapsed = $last->created_at->diffInSeconds(now());
        return max(0, self::RESEND_COOLDOWN - $elapsed);
    }
}
