<?php

namespace App\Http\Controllers\Auth;

use App\Enums\AksiAudit;
use App\Http\Controllers\Controller;
use App\Services\AuditService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        // Rate limiting: maks 5 attempt per email per menit
        $throttleKey = Str::lower($request->email) . '|' . $request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            // Audit login gagal karena throttle
            $user = \App\Models\User::where('email', $request->email)->first();
            if ($user) {
                AuditService::auth(AksiAudit::USER_LOGIN_FAILED, $user->id, [
                    'reason' => 'rate_limited',
                    'retry_after_seconds' => $seconds,
                ]);
            }

            throw ValidationException::withMessages([
                'email' => "Terlalu banyak percobaan login. Coba lagi dalam {$seconds} detik.",
            ]);
        }

        if (! Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            RateLimiter::hit($throttleKey, 900); // lockout 15 menit setelah 5x gagal

            // Audit login gagal
            $user = \App\Models\User::where('email', $request->email)->first();
            if ($user) {
                AuditService::auth(AksiAudit::USER_LOGIN_FAILED, $user->id, ['reason' => 'wrong_password']);
            }

            throw ValidationException::withMessages([
                'email' => 'Email atau password tidak sesuai.',
            ]);
        }

        RateLimiter::clear($throttleKey);

        $user = Auth::user();

        // Cek apakah akun aktif
        if (! $user->is_active) {
            Auth::logout();
            throw ValidationException::withMessages([
                'email' => 'Akun Anda telah dinonaktifkan. Hubungi administrator.',
            ]);
        }

        $request->session()->regenerate();

        // Update last login
        $user->update([
            'last_login_at' => now(),
            'last_login_ip' => $request->ip(),
        ]);

        AuditService::auth(AksiAudit::USER_LOGIN, $user->id);

        // Jika belum verifikasi email → ke halaman OTP
        if (! $user->email_verified) {
            return redirect()->route('verification.notice');
        }

        return redirect()->intended(route('dashboard'));
    }

    public function destroy(Request $request): RedirectResponse
    {
        $userId = auth()->id();

        AuditService::auth(AksiAudit::USER_LOGOUT, $userId);

        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
