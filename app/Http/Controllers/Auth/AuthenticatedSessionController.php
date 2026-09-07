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

        $throttleKey = Str::lower($request->email) . '|' . $request->ip();

        // ── Rate limiter check ────────────────────────────────────────────────
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            $user = \App\Models\User::where('email', $request->email)->first();
            if ($user) {
                AuditService::auth(AksiAudit::USER_LOGIN_FAILED, $user->id, [
                    'reason'               => 'rate_limited',
                    'retry_after_seconds'  => $seconds,
                ]);
            }

            throw ValidationException::withMessages([
                'email' => "Terlalu banyak percobaan login. Coba lagi dalam {$seconds} detik.",
            ]);
        }

        // ── Cek account state sebelum attempt ────────────────────────────────
        $user = \App\Models\User::where('email', $request->email)->first();

        if ($user && $user->isSuspended()) {
            throw ValidationException::withMessages([
                'email' => 'Akun Anda telah disuspend. Hubungi administrator.',
            ]);
        }

        if ($user && $user->isLocked()) {
            throw ValidationException::withMessages([
                'email' => 'Akun Anda terkunci. Hubungi administrator untuk membuka kunci.',
            ]);
        }

        // ── Attempt login ─────────────────────────────────────────────────────
        if (! Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            RateLimiter::hit($throttleKey, 900);

            if ($user) {
                $newCount = $user->failed_login_count + 1;
                $updates  = ['failed_login_count' => $newCount];

                // Lock akun jika mencapai threshold (5x gagal)
                if ($newCount >= 5 && ! $user->isLocked()) {
                    $updates['locked_at'] = now();

                    AuditService::log(
                        AksiAudit::USER_ACCOUNT_LOCKED,
                        null,           // system action, bukan user action
                        $user,
                        ['locked_at' => null],
                        ['locked_at' => now()->toDateTimeString(), 'reason' => 'failed_login_threshold'],
                    );
                }

                $user->update($updates);

                AuditService::auth(AksiAudit::USER_LOGIN_FAILED, $user->id, [
                    'reason'             => 'wrong_password',
                    'failed_login_count' => $newCount,
                ]);
            }

            throw ValidationException::withMessages([
                'email' => 'Email atau password tidak sesuai.',
            ]);
        }

        RateLimiter::clear($throttleKey);

        $user = Auth::user();

        if (! $user->is_active) {
            Auth::logout();
            throw ValidationException::withMessages([
                'email' => 'Akun Anda telah dinonaktifkan. Hubungi administrator.',
            ]);
        }

        $request->session()->regenerate();

        // Reset failed_login_count saat login berhasil
        $user->update([
            'last_login_at'      => now(),
            'last_login_ip'      => $request->ip(),
            'failed_login_count' => 0,
        ]);

        AuditService::auth(AksiAudit::USER_LOGIN, $user->id);

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
