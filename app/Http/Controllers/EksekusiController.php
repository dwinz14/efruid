<?php

namespace App\Http\Controllers;

use App\Enums\AksiAudit;
use App\Enums\StatusPermohonan;
use App\Jobs\GenerateFruidPdf;
use App\Models\ApprovalLog;
use App\Models\Permohonan;
use App\Services\AuditService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class EksekusiController extends Controller
{
    // ── Daftar permohonan PENDING_IT ──────────────────────────────────────

    public function index(Request $request): View
    {
        $query = Permohonan::with(['pemohon', 'kantor'])
            ->where('status', StatusPermohonan::PENDING_IT)
            ->latest();

        if ($request->filled('kantor_id')) {
            $query->where('kantor_id', $request->kantor_id);
        }

        if ($request->filled('jenis')) {
            $query->where('jenis_permohonan', $request->jenis);
        }

        $pending      = $query->paginate(10)->withQueryString();
        $pendingCount = Permohonan::where('status', StatusPermohonan::PENDING_IT)->count();

        // Untuk filter kantor
        $kantors = \App\Models\Kantor::where('is_active', true)->orderBy('nama')->get();

        return view('eksekusi.index', compact('pending', 'pendingCount', 'kantors'));
    }

    // ── Detail untuk IT Staff ─────────────────────────────────────────────

    public function show(Permohonan $permohonan): View
    {
        // IT Staff bisa lihat semua PENDING_IT
        if (
            $permohonan->status !== StatusPermohonan::PENDING_IT
            && $permohonan->status !== StatusPermohonan::EXECUTED
        ) {
            abort(403, 'Permohonan ini tidak dalam status yang dapat diproses IT.');
        }

        $permohonan->load('pemohon', 'kantor', 'atasan', 'approvalLogs.user');

        return view('eksekusi.show', compact('permohonan'));
    }

    // ── Eksekusi: tandai selesai + dispatch PDF job ───────────────────────

    public function execute(Request $request, Permohonan $permohonan): RedirectResponse
    {
        if ($permohonan->status !== StatusPermohonan::PENDING_IT) {
            return back()->withErrors(['error' => 'Status permohonan tidak valid untuk dieksekusi.']);
        }

        $executor = auth()->user();

        // Update status
        $permohonan->update(['status' => StatusPermohonan::EXECUTED]);

        // Catat approval log
        ApprovalLog::create([
            'permohonan_id' => $permohonan->id,
            'user_id'       => $executor->id,
            'aksi'          => 'executed',
            'status_dari'   => StatusPermohonan::PENDING_IT->value,
            'status_ke'     => StatusPermohonan::EXECUTED->value,
            'catatan'       => $request->input('catatan'),
            'ip_address'    => $request->ip(),
        ]);

        AuditService::log(
            AksiAudit::PERMOHONAN_EXECUTED,
            $executor->id,
            $permohonan,
            ['status' => StatusPermohonan::PENDING_IT->value],
            ['status' => StatusPermohonan::EXECUTED->value],
            $permohonan->nomor_dokumen,
        );

        // Dispatch PDF generation ke queue
        GenerateFruidPdf::dispatch($permohonan->id, $executor->id);

        return redirect()->route('eksekusi.index')
            ->with('success', "Permohonan {$permohonan->nomor_dokumen} berhasil dieksekusi. PDF sedang digenerate.");
    }

    // ── Download PDF ──────────────────────────────────────────────────────

    public function downloadPdf(Permohonan $permohonan): Response|RedirectResponse
    {
        $user = auth()->user();

        // Hanya pemohon sendiri, IT staff, atau super admin yang bisa download
        $boleh = $user->id === $permohonan->pemohon_id
            || $user->isItStaff()
            || $user->isSuperAdmin();

        if (! $boleh) {
            abort(403);
        }

        if (! $permohonan->pdf_path || ! Storage::exists($permohonan->pdf_path)) {
            // PDF belum selesai digenerate
            return back()->withErrors([
                'error' => 'PDF belum tersedia. Mungkin masih dalam proses generate, coba beberapa saat lagi.'
            ]);
        }

        $nomorBersih = preg_replace('/[^A-Za-z0-9\-]/', '_', $permohonan->nomor_dokumen ?? $permohonan->id);
        $filename    = "FRUID_{$nomorBersih}.pdf";

        return response(Storage::get($permohonan->pdf_path), 200, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => "inline; filename=\"{$filename}\"",
            'Cache-Control'       => 'private, max-age=3600',
        ]);
    }
}
