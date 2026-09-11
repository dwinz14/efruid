<?php

namespace App\Http\Controllers;

use App\Enums\AksiAudit;
use App\Models\Permohonan;
use App\Services\AuditService;
use App\Services\DocumentRenderer;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class VerifikasiController extends Controller
{
    public function __construct(private DocumentRenderer $renderer) {}

    // ── Halaman utama verifikasi (mobile-first) ───────────────────────────

    public function show(Request $request, string $token): Response
    {
        $permohonan = $this->findByToken($token);

        if (! $permohonan) {
            return response(view('verifikasi.not-found')->render(), 404)
                ->withHeaders($this->securityHeaders());
        }

        // Log akses publik — wrapped try/catch agar tidak break halaman
        try {
            AuditService::log(
                AksiAudit::DOKUMEN_DIVERIFIKASI,
                null,
                $permohonan,
                [],
                [
                    'ip'         => $request->ip(),
                    'user_agent' => substr($request->userAgent() ?? '', 0, 200),
                ],
                $permohonan->nomor_dokumen,
            );
        } catch (\Throwable) {
            // Audit opsional — tidak boleh menghentikan halaman verifikasi
        }

        $html = view('verifikasi.show', [
            'permohonan' => $permohonan,
            'stamps'     => $permohonan->verification_stamps ?? [],
            'token'      => $token,
        ])->render();

        return response($html)->withHeaders($this->securityHeaders());
    }

    // ── Serve dokumen FRUID untuk iframe (token yang sama) ────────────────

    public function document(string $token): Response
    {
        $permohonan = $this->findByToken($token);

        if (! $permohonan) {
            abort(404);
        }

        $html = view('dokumen.fruid', $this->renderer->prepare($permohonan))->render();

        return response($html)->withHeaders([
            'Content-Type'    => 'text/html; charset=utf-8',
            'X-Frame-Options' => 'SAMEORIGIN',
            'Cache-Control'   => 'no-store, no-cache',
            'Pragma'          => 'no-cache',
        ]);
    }

    // ── Private helpers ───────────────────────────────────────────────────

    private function findByToken(string $token): ?Permohonan
    {
        return Permohonan::where('verifikasi_token', $token)
            ->with(['pemohon', 'kantor', 'atasan', 'executor'])
            ->first();
    }

    private function securityHeaders(): array
    {
        $csp = [
            "frame-ancestors 'none'",
            "default-src 'self' 'unsafe-inline' 'unsafe-eval' http://localhost:* http://127.0.0.1:* ws://localhost:* ws://127.0.0.1:* https: data: blob:",
            "frame-src 'self' data: blob:",
            "img-src 'self' data: blob: https:",
            "font-src 'self' data: https://fonts.gstatic.com https:",
            "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdn.tailwindcss.com https:",
            "script-src 'self' 'unsafe-inline' 'unsafe-eval' http://localhost:* http://127.0.0.1:* https:",
        ];

        return [
            'X-Frame-Options'         => 'DENY',
            'X-Content-Type-Options'  => 'nosniff',
            'Referrer-Policy'         => 'no-referrer',
            'Cache-Control'           => 'no-store, no-cache, must-revalidate',
            'Pragma'                  => 'no-cache',
            'Content-Security-Policy' => implode('; ', $csp),
        ];
    }
}
