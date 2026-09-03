<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
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
        $query = Permohonan::with(['pemohon', 'kantor', 'executor'])
            ->where('status', StatusPermohonan::PENDING_IT)
            ->orderByRaw('CASE WHEN executor_id = ? THEN 0 WHEN executor_id IS NULL THEN 1 ELSE 2 END', [auth()->id()])
            ->latest();

        if ($request->filled('kantor_id')) {
            $query->where('kantor_id', $request->kantor_id);
        }

        if ($request->filled('jenis')) {
            $query->where('jenis_permohonan', $request->jenis);
        }

        $pending         = $query->paginate(10)->withQueryString();
        $pendingCount    = Permohonan::where('status', StatusPermohonan::PENDING_IT)->count();
        $myClaimedCount  = Permohonan::where('status', StatusPermohonan::PENDING_IT)
            ->where('executor_id', auth()->id())
            ->count();

        $kantors = \App\Models\Kantor::where('is_active', true)->orderBy('nama')->get();

        return view('eksekusi.index', compact('pending', 'pendingCount', 'myClaimedCount', 'kantors'));
    }

    // ── Detail untuk IT Staff ─────────────────────────────────────────────

    public function show(Permohonan $permohonan): View
    {
        if (
            $permohonan->status !== StatusPermohonan::PENDING_IT
            && $permohonan->status !== StatusPermohonan::EXECUTED
        ) {
            abort(403, 'Permohonan ini tidak dalam status yang dapat diproses IT.');
        }

        $permohonan->load('pemohon', 'kantor', 'atasan', 'executor', 'approvalLogs.user');

        return view('eksekusi.show', compact('permohonan'));
    }

    // ── Klaim permohonan ──────────────────────────────────────────────────

    public function claim(Request $request, Permohonan $permohonan): RedirectResponse
    {
        if ($permohonan->status !== StatusPermohonan::PENDING_IT) {
            return back()->withErrors(['error' => 'Permohonan tidak dalam status PENDING IT.']);
        }

        if ($permohonan->isClaimed()) {
            return back()->withErrors(['error' => 'Permohonan ini sudah diambil oleh anggota tim lain.']);
        }

        $user = auth()->user();

        $permohonan->update([
            'executor_id' => $user->id,
            'claimed_at'  => now(),
        ]);

        AuditService::log(
            AksiAudit::PERMOHONAN_CLAIMED,
            $user->id,
            $permohonan,
            null,
            ['executor' => $user->name],
            $permohonan->nomor_dokumen,
        );

        return redirect()->route('eksekusi.show', $permohonan)
            ->with('success', "Permohonan {$permohonan->nomor_dokumen} berhasil diambil. Silakan lakukan eksekusi.");
    }

    // ── Lepas klaim ───────────────────────────────────────────────────────

    public function unclaim(Request $request, Permohonan $permohonan): RedirectResponse
    {
        if ($permohonan->status !== StatusPermohonan::PENDING_IT) {
            return back()->withErrors(['error' => 'Permohonan tidak dalam status PENDING IT.']);
        }

        $user = auth()->user();

        // Hanya pengklaim sendiri atau super_admin yang boleh lepas
        if (! $permohonan->isClaimedBy($user->id) && ! $user->isSuperAdmin()) {
            abort(403, 'Anda tidak berhak melepas klaim ini.');
        }

        $previousExecutor = $permohonan->executor?->name ?? 'N/A';

        $permohonan->update([
            'executor_id' => null,
            'claimed_at'  => null,
        ]);

        AuditService::log(
            AksiAudit::PERMOHONAN_UNCLAIMED,
            $user->id,
            $permohonan,
            ['executor' => $previousExecutor],
            null,
            $permohonan->nomor_dokumen,
        );

        return redirect()->route('eksekusi.index')
            ->with('success', "Klaim permohonan {$permohonan->nomor_dokumen} berhasil dilepas.");
    }

    // ── Eksekusi: tandai selesai + dispatch PDF job ───────────────────────

    public function execute(Request $request, Permohonan $permohonan): RedirectResponse
    {
        if ($permohonan->status !== StatusPermohonan::PENDING_IT) {
            return back()->withErrors(['error' => 'Status permohonan tidak valid untuk dieksekusi.']);
        }

        $executor = auth()->user();

        // Harus sudah diklaim oleh diri sendiri
        if (! $permohonan->isClaimedBy($executor->id)) {
            return back()->withErrors(['error' => 'Anda harus "Ambil" permohonan ini sebelum bisa mengeksekusi.']);
        }

        // ── Embed tanda tangan executor ───────────────────────────────────────
        $ttdExecutorPath = null;
        if ($executor->signature_path && Storage::exists($executor->signature_path)) {
            $dest = "signatures/snapshots/{$permohonan->id}_executor.png";
            Storage::copy($executor->signature_path, $dest);
            $ttdExecutorPath = $dest;
        }

        // ── Generate verification stamp untuk Administrator USSI ──────────────
        $timestamp = Carbon::now()->setTimezone('Asia/Jakarta')->format('d/m/Y H:i:s') . ' WIB';
        $hashInput = implode('|', [
            $permohonan->nomor_dokumen ?? $permohonan->id,
            $executor->id,
            'Administrator USSI',
            $timestamp,
        ]);
        $stampExecutor = [
            'role'      => 'Administrator USSI',
            'nama'      => $executor->name,
            'jabatan'   => $executor->jabatan_label ?? 'IT Staff',
            'timestamp' => $timestamp,
            'hash'      => hash('sha256', $hashInput),
        ];

        $stamps   = $permohonan->verification_stamps ?? [];
        $stamps[] = $stampExecutor;

        // ── Update permohonan ─────────────────────────────────────────────────
        $permohonan->update([
            'status'              => StatusPermohonan::EXECUTED,
            'nama_executor'       => $executor->name,
            'ttd_executor_path'   => $ttdExecutorPath,
            'verification_stamps' => $stamps,
        ]);

        // ── Catat approval log ────────────────────────────────────────────────
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

        GenerateFruidPdf::dispatch($permohonan->id, $executor->id);

        return redirect()->route('eksekusi.index')
            ->with('success', "Permohonan {$permohonan->nomor_dokumen} berhasil dieksekusi. PDF sedang digenerate.");
    }

    // ── Riwayat Eksekusi IT ───────────────────────────────────────────────────

    public function riwayat(Request $request): View
    {
        $user = auth()->user();

        $riwayat = Permohonan::with(['pemohon', 'kantor'])
            ->where('executor_id', $user->id)
            ->where('status', StatusPermohonan::EXECUTED)
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('eksekusi.riwayat', compact('riwayat'));
    }

    // ── Download PDF ──────────────────────────────────────────────────────

    public function downloadPdf(Permohonan $permohonan): Response|RedirectResponse
    {
        $user = auth()->user();

        $boleh = $user->id === $permohonan->pemohon_id
            || $user->isItStaff()
            || $user->isSuperAdmin();

        if (! $boleh) {
            abort(403);
        }

        if (! $permohonan->pdf_path || ! Storage::exists($permohonan->pdf_path)) {
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
