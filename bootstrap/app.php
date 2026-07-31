<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\CheckRole;
use App\Http\Middleware\EnsureEmailVerified;
use App\Http\Middleware\EnsureUserActive;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Alias middleware
        $middleware->alias([
            'role'             => CheckRole::class,
            'email.verified'   => EnsureEmailVerified::class,
            'user.active'      => EnsureUserActive::class,
        ]);

        // Tambahkan ke grup 'web' — aktif untuk semua request web
        $middleware->web(append: [
            EnsureUserActive::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
