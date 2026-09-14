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
            return response(view('verifikasi.not-found')->render(), 404);
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

        return response(view('verifikasi.show', [
            'permohonan' => $permohonan,
            'stamps'     => $permohonan->verification_stamps ?? [],
            'token'      => $token,
        ])->render());
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
}
