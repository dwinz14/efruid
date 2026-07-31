<?php

namespace App\Http\Controllers\Auth;

use App\Enums\AksiAudit;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\AuditService;
use App\Services\OtpService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class PasswordResetController extends Controller
{
    public function __construct(private OtpService $otpService) {}

    /** Step 1: Input email */
    public function request(): View
    {
        return view('auth.forgot-password');
    }

    /** Step 1: Kirim OTP ke email */
    public function sendOtp(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $user = User::where('email', $request->email)
            ->where('is_active', true)
            ->first();

        // Response sama meski email tidak ditemukan (security: jangan bocorkan info)
        if ($user) {
            $sent = $this->otpService->send($user, 'reset_password');
            if ($sent) {
                AuditService::auth(AksiAudit::USER_OTP_SENT, $user->id, ['purpose' => 'reset_password']);
            }
        }

        // Simpan email di session untuk step berikutnya
        Session::put('reset_email', $request->email);

        return redirect()->route('password.otp')
            ->with('success', 'Jika email terdaftar, kode OTP akan dikirim dalam beberapa menit.');
    }

    /** Step 2: Input OTP */
    public function otpForm(): View|RedirectResponse
    {
        if (! Session::has('reset_email')) {
            return redirect()->route('password.request');
        }

        $email = Session::get('reset_email');
        $user  = User::where('email', $email)->first();
        $cooldown = $user ? $this->otpService->resendCooldownSeconds($user, 'reset_password') : 0;

        return view('auth.reset-password-otp', [
            'email'    => $email,
            'cooldown' => $cooldown,
        ]);
    }

    /** Step 2: Verifikasi OTP, lanjut ke step 3 */
    public function verifyOtp(Request $request): RedirectResponse
    {
        $request->validate([
            'otp' => ['required', 'string', 'size:6', 'regex:/^\d{6}$/'],
        ]);

        if (! Session::has('reset_email')) {
            return redirect()->route('password.request');
        }

        $user = User::where('email', Session::get('reset_email'))->first();

        if (! $user) {
            return redirect()->route('password.request')
                ->withErrors(['email' => 'Email tidak ditemukan.']);
        }

        $result = $this->otpService->verify($user, 'reset_password', $request->otp);

        if ($result !== 'valid') {
            AuditService::auth(AksiAudit::USER_OTP_FAILED, $user->id, [
                'purpose' => 'reset_password',
                'result'  => $result,
            ]);

            $message = match ($result) {
                'invalid'     => 'Kode OTP tidak valid.',
                'expired'     => 'Kode OTP sudah kedaluwarsa. Minta kode baru.',
                'max_attempt' => 'Terlalu banyak percobaan. Minta kode baru.',
                default       => 'Verifikasi gagal.',
            };

            return back()->withErrors(['otp' => $message]);
        }

        AuditService::auth(AksiAudit::USER_OTP_VERIFIED, $user->id, ['purpose' => 'reset_password']);

        // Tandai OTP sudah valid di session, lanjut ke form password baru
        Session::put('reset_verified', true);
        Session::put('reset_user_id', $user->id);

        return redirect()->route('password.new');
    }

    /** Step 3: Form password baru */
    public function newPasswordForm(): View|RedirectResponse
    {
        if (! Session::get('reset_verified')) {
            return redirect()->route('password.request');
        }

        return view('auth.new-password');
    }

    /** Step 3: Simpan password baru */
    public function updatePassword(Request $request): RedirectResponse
    {
        if (! Session::get('reset_verified') || ! Session::get('reset_user_id')) {
            return redirect()->route('password.request');
        }

        $request->validate([
            'password' => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
        ], [
            'password.min' => 'Password minimal 8 karakter.',
        ]);

        $user = User::findOrFail(Session::get('reset_user_id'));

        $user->update(['password' => Hash::make($request->password)]);

        AuditService::auth(AksiAudit::USER_PASSWORD_RESET, $user->id);

        // Bersihkan session reset
        Session::forget(['reset_email', 'reset_verified', 'reset_user_id']);

        return redirect()->route('login')
            ->with('success', 'Password berhasil diubah. Silakan login dengan password baru Anda.');
    }

    /** Resend OTP reset password */
    public function resendOtp(Request $request): RedirectResponse
    {
        if (! Session::has('reset_email')) {
            return redirect()->route('password.request');
        }

        $user = User::where('email', Session::get('reset_email'))->where('is_active', true)->first();

        if (! $user) {
            return redirect()->route('password.request');
        }

        $sent = $this->otpService->send($user, 'reset_password');

        if (! $sent) {
            $cooldown = $this->otpService->resendCooldownSeconds($user, 'reset_password');
            return back()->withErrors(['otp' => "Tunggu {$cooldown} detik sebelum meminta kode baru."]);
        }

        AuditService::auth(AksiAudit::USER_OTP_SENT, $user->id, ['purpose' => 'reset_password']);

        return back()->with('success', 'Kode OTP baru telah dikirim.');
    }
}
