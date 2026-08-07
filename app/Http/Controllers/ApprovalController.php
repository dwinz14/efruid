<?php

namespace App\Http\Controllers;

use App\Enums\RoleUser;
use App\Enums\StatusPermohonan;
use App\Models\Permohonan;
use App\Services\ApprovalService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class ApprovalController extends Controller
{
    public function __construct(private ApprovalService $service) {}

    // ── Dashboard Atasan ──────────────────────────────────────────────────

    public function atasanIndex(Request $request): View
    {
        $user = auth()->user();

        $query = Permohonan::with(['pemohon', 'kantor'])
            ->where('atasan_id', $user->id)
            ->where('status', StatusPermohonan::PENDING_ATASAN)
            ->latest();

        if ($request->filled('kantor_id')) {
            $query->where('kantor_id', $request->kantor_id);
        }

        $pending     = $query->paginate(10)->withQueryString();
        $pendingCount = Permohonan::where('atasan_id', $user->id)
            ->where('status', StatusPermohonan::PENDING_ATASAN)
            ->count();

        return view('approval.atasan.index', compact('pending', 'pendingCount'));
    }

    // ── Detail untuk Atasan ───────────────────────────────────────────────

    public function atasanShow(Permohonan $permohonan): View
    {
        Gate::authorize('approveAsAtasan', $permohonan);

        $permohonan->load('pemohon', 'kantor', 'atasan', 'approvalLogs.user');

        return view('approval.atasan.show', compact('permohonan'));
    }

    // ── Approve oleh Atasan ───────────────────────────────────────────────

    public function atasanApprove(Request $request, Permohonan $permohonan): RedirectResponse
    {
        Gate::authorize('approveAsAtasan', $permohonan);

        $this->service->approveAtasan($permohonan, auth()->user());

        $pesan = $permohonan->fresh()->status === StatusPermohonan::PENDING_IT
            ? 'Permohonan disetujui dan diteruskan ke IT.'
            : 'Permohonan disetujui dan diteruskan ke Direktur.';

        return redirect()->route('approval.atasan.index')
            ->with('success', $pesan);
    }

    // ── Reject oleh Atasan ────────────────────────────────────────────────

    public function atasanReject(Request $request, Permohonan $permohonan): RedirectResponse
    {
        Gate::authorize('rejectPermohonan', $permohonan);

        $request->validate([
            'alasan_reject' => ['required', 'string', 'min:10', 'max:500'],
        ], [
            'alasan_reject.required' => 'Alasan penolakan wajib diisi.',
            'alasan_reject.min'      => 'Alasan minimal 10 karakter.',
        ]);

        $this->service->reject($permohonan, auth()->user(), $request->alasan_reject);

        return redirect()->route('approval.atasan.index')
            ->with('success', 'Permohonan berhasil ditolak.');
    }

    // ── Dashboard Dirut ───────────────────────────────────────────────────

    public function dirutIndex(Request $request): View
    {
        $query = Permohonan::with(['pemohon', 'kantor'])
            ->where('status', StatusPermohonan::PENDING_DIRUT)
            ->latest();

        if ($request->filled('kantor_id')) {
            $query->where('kantor_id', $request->kantor_id);
        }

        $pending      = $query->paginate(10)->withQueryString();
        $pendingCount = Permohonan::where('status', StatusPermohonan::PENDING_DIRUT)->count();

        return view('approval.dirut.index', compact('pending', 'pendingCount'));
    }

    // ── Detail untuk Dirut ────────────────────────────────────────────────

    public function dirutShow(Permohonan $permohonan): View
    {
        Gate::authorize('approveAsDirut', $permohonan);

        $permohonan->load('pemohon', 'kantor', 'atasan', 'approvalLogs.user');

        return view('approval.dirut.show', compact('permohonan'));
    }

    // ── Approve oleh Dirut ────────────────────────────────────────────────

    public function dirutApprove(Request $request, Permohonan $permohonan): RedirectResponse
    {
        Gate::authorize('approveAsDirut', $permohonan);

        $this->service->approveDirut($permohonan, auth()->user());

        return redirect()->route('approval.dirut.index')
            ->with('success', 'Permohonan disetujui dan diteruskan ke IT.');
    }

    // ── Reject oleh Dirut ─────────────────────────────────────────────────

    public function dirutReject(Request $request, Permohonan $permohonan): RedirectResponse
    {
        Gate::authorize('rejectPermohonan', $permohonan);

        $request->validate([
            'alasan_reject' => ['required', 'string', 'min:10', 'max:500'],
        ], [
            'alasan_reject.required' => 'Alasan penolakan wajib diisi.',
            'alasan_reject.min'      => 'Alasan minimal 10 karakter.',
        ]);

        $this->service->reject($permohonan, auth()->user(), $request->alasan_reject);

        return redirect()->route('approval.dirut.index')
            ->with('success', 'Permohonan berhasil ditolak.');
    }

    // ── Revisi (dari halaman detail pemohon) ──────────────────────────────

    public function revise(Permohonan $permohonan): RedirectResponse
    {
        Gate::authorize('revisePermohonan', $permohonan);

        $this->service->revise($permohonan, auth()->user());

        return redirect()->route('permohonan.edit', $permohonan)
            ->with('success', 'Permohonan dikembalikan ke draft. Silakan edit dan submit ulang.');
    }
}
