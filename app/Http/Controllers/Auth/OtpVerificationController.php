<?php

namespace App\Http\Controllers\Auth;

use App\Enums\AksiAudit;
use App\Http\Controllers\Controller;
use App\Services\AuditService;
use App\Services\OtpService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OtpVerificationController extends Controller
{
    public function __construct(private OtpService $otpService) {}

    /** Tampilkan halaman input OTP */
    public function notice(): View|RedirectResponse
    {
        $user = auth()->user();

        if ($user->email_verified) {
            return redirect()->intended(route('dashboard'));
        }

        $cooldown = $this->otpService->resendCooldownSeconds($user, 'verify_email');

        return view('auth.verify-otp', [
            'purpose'  => 'verify_email',
            'cooldown' => $cooldown,
        ]);
    }

    /** Proses verifikasi OTP */
    public function verify(Request $request): RedirectResponse
    {
        $request->validate([
            'otp' => ['required', 'string', 'size:6', 'regex:/^\d{6}$/'],
        ], [
            'otp.required' => 'Kode OTP wajib diisi.',
            'otp.size'     => 'Kode OTP harus 6 digit.',
            'otp.regex'    => 'Kode OTP hanya boleh berisi angka.',
        ]);

        $user   = auth()->user();
        $result = $this->otpService->verify($user, 'verify_email', $request->otp);

        if ($result === 'valid') {
            $user->update(['email_verified' => true]);
            AuditService::auth(AksiAudit::USER_OTP_VERIFIED, $user->id, ['purpose' => 'verify_email']);

            return redirect()->route('dashboard')
                ->with('success', 'Email berhasil diverifikasi. Selamat datang di eFRUID!');
        }

        AuditService::auth(AksiAudit::USER_OTP_FAILED, $user->id, [
            'purpose' => 'verify_email',
            'result'  => $result,
        ]);

        $message = match($result) {
            'invalid'     => 'Kode OTP tidak valid. Periksa kembali kode yang dikirim ke email Anda.',
            'expired'     => 'Kode OTP sudah kedaluwarsa. Silakan minta kode baru.',
            'max_attempt' => 'Terlalu banyak percobaan. Silakan minta kode OTP baru.',
            'not_found'   => 'Tidak ada OTP aktif. Silakan minta kode baru.',
            default       => 'Verifikasi gagal. Silakan coba lagi.',
        };

        return back()->withErrors(['otp' => $message]);
    }

    /** Kirim ulang OTP */
    public function resend(Request $request): RedirectResponse
    {
        $user = auth()->user();

        if ($user->email_verified) {
            return redirect()->route('dashboard');
        }

        $sent = $this->otpService->send($user, 'verify_email');

        if (! $sent) {
            $cooldown = $this->otpService->resendCooldownSeconds($user, 'verify_email');
            return back()->withErrors([
                'otp' => "Tunggu {$cooldown} detik sebelum meminta kode baru.",
            ]);
        }

        AuditService::auth(AksiAudit::USER_OTP_SENT, $user->id, ['purpose' => 'verify_email']);

        return back()->with('success', 'Kode OTP baru telah dikirim ke email Anda.');
    }
}
