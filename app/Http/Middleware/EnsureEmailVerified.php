<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureEmailVerified
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && ! $user->email_verified) {
            // Jika sudah auth tapi belum verifikasi, arahkan ke halaman OTP
            return redirect()->route('verification.notice');
        }

        return $next($request);
    }
}
