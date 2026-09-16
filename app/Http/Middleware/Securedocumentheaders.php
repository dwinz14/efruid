<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecureDocumentHeaders
{
    public function handle(Request $request, Closure $next, string $frame = 'sameorigin'): Response
    {
        $response = $next($request);

        $isDeny         = strtolower($frame) === 'deny';
        $frameOption    = $isDeny ? 'DENY' : 'SAMEORIGIN';
        $frameAncestors = $isDeny ? "frame-ancestors 'none'" : "frame-ancestors 'self'";

        $csp = [
            $frameAncestors,
            "default-src 'self' 'unsafe-inline' 'unsafe-eval' http://localhost:* http://127.0.0.1:* ws://localhost:* ws://127.0.0.1:* https: data: blob:",
            "frame-src 'self' data: blob:",
            "img-src 'self' data: blob: https:",
            "font-src 'self' data: https://fonts.gstatic.com https:",
            "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdn.tailwindcss.com https:",
            "script-src 'self' 'unsafe-inline' 'unsafe-eval' http://localhost:* http://127.0.0.1:* https:",
        ];

        return $response->withHeaders([
            'X-Frame-Options'         => $frameOption,
            'X-Content-Type-Options'  => 'nosniff',
            'Referrer-Policy'         => 'no-referrer',
            'Cache-Control'           => 'no-store, no-cache, must-revalidate, private',
            'Pragma'                  => 'no-cache',
            'Content-Security-Policy' => implode('; ', $csp),
        ]);
    }
}
