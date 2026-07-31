<?php

namespace App\Http\Middleware;

use App\Enums\RoleUser;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Penggunaan di route: ->middleware('role:atasan,it_staff')
     * User harus memiliki SALAH SATU dari role yang disebutkan.
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user || ! $user->is_active) {
            abort(403, 'Akun Anda tidak aktif atau tidak memiliki akses.');
        }

        // Super admin bypass semua role check
        if ($user->isSuperAdmin()) {
            return $next($request);
        }

        foreach ($roles as $role) {
            if ($user->hasRole($role)) {
                return $next($request);
            }
        }

        abort(403, 'Anda tidak memiliki akses ke halaman ini.');
    }
}
