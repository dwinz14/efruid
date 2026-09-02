<?php

namespace App\Http\Controllers;

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
    // Menampilkan semua permohonan di mana user ini adalah atasan_id

    public function atasanIndex(Request $request): View
    {
        $user = auth()->user();

        $query = Permohonan::with(['pemohon', 'kantor'])
            ->where('atasan_id', $user->id)
            ->where('status', StatusPermohonan::PENDING_ATASAN)
            ->latest();

        $pending      = $query->paginate(10)->withQueryString();
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

        $fresh = $permohonan->fresh();
        $pesan = match ($fresh->status) {
            StatusPermohonan::PENDING_IT   => 'Permohonan disetujui dan diteruskan ke IT.',
            StatusPermohonan::PENDING_DIRUT => 'Permohonan disetujui dan diteruskan ke Direktur.',
            default => 'Permohonan berhasil disetujui.',
        };

        return redirect()->route('approval.atasan.index')->with('success', $pesan);
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

    // ── Dashboard Dirut (Unified) ─────────────────────────────────────────
    // Menampilkan:
    //   1. PENDING_ATASAN di mana atasan_id = dirut.id (permohonan L3/L2)
    //   2. PENDING_DIRUT semua kantor (permohonan rangkap L5/L4)

    public function dirutIndex(Request $request): View
    {
        $user = auth()->user();

        // Query 1: Permohonan L3/L2 yang menunggu approve Dirut sebagai atasan
        $pendingAsAtasan = Permohonan::with(['pemohon', 'kantor'])
            ->where('atasan_id', $user->id)
            ->where('status', StatusPermohonan::PENDING_ATASAN)
            ->get();

        // Query 2: Permohonan rangkap yang sudah diapprove atasan, menunggu Dirut
        $pendingDirut = Permohonan::with(['pemohon', 'kantor'])
            ->where('status', StatusPermohonan::PENDING_DIRUT)
            ->get();

        // Gabung dan deduplikasi (edge case: Dirut bisa ada di keduanya)
        $allPending = $pendingAsAtasan->merge($pendingDirut)->unique('id')->sortByDesc('updated_at');

        $pendingCount = $allPending->count();

        // Pagination manual untuk koleksi gabungan
        $page        = $request->input('page', 1);
        $perPage     = 10;
        $pending     = new \Illuminate\Pagination\LengthAwarePaginator(
            $allPending->forPage($page, $perPage)->values(),
            $allPending->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('approval.dirut.index', compact('pending', 'pendingCount'));
    }

    // ── Detail untuk Dirut ────────────────────────────────────────────────

    public function dirutShow(Permohonan $permohonan): View
    {
        $user = auth()->user();

        // Boleh lihat jika: dia adalah atasan_id (PENDING_ATASAN)
        // atau status PENDING_DIRUT (semua Dirut boleh approve)
        $boleh = ($permohonan->atasan_id === $user->id
            && $permohonan->status === StatusPermohonan::PENDING_ATASAN)
            || ($user->isDirut()
                && $permohonan->status === StatusPermohonan::PENDING_DIRUT);

        if (! $boleh && ! $user->isSuperAdmin()) {
            abort(403);
        }

        $permohonan->load('pemohon', 'kantor', 'atasan', 'approvalLogs.user');

        // Tentukan aksi yang bisa dilakukan Dirut di permohonan ini
        $canApproveAsAtasan = $permohonan->status === StatusPermohonan::PENDING_ATASAN
            && $permohonan->atasan_id === $user->id;
        $canApproveAsDirut  = $permohonan->status === StatusPermohonan::PENDING_DIRUT
            && $user->isDirut();

        return view('approval.dirut.show', compact(
            'permohonan',
            'canApproveAsAtasan',
            'canApproveAsDirut'
        ));
    }

    // ── Approve oleh Dirut sebagai Atasan (L3/L2) ─────────────────────────

    public function dirutApproveAsAtasan(Request $request, Permohonan $permohonan): RedirectResponse
    {
        Gate::authorize('approveAsAtasan', $permohonan);

        $this->service->approveAtasan($permohonan, auth()->user());

        return redirect()->route('approval.dirut.index')
            ->with('success', 'Permohonan disetujui dan diteruskan ke IT.');
    }

    // ── Approve oleh Dirut (PENDING_DIRUT — form rangkap L5/L4) ──────────

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

    // ── Revisi (dari halaman pemohon) ─────────────────────────────────────

    public function revise(Permohonan $permohonan): RedirectResponse
    {
        Gate::authorize('revisePermohonan', $permohonan);

        $this->service->revise($permohonan, auth()->user());

        return redirect()->route('permohonan.edit', $permohonan)
            ->with('success', 'Permohonan dikembalikan ke draft. Silakan edit dan submit ulang.');
    }
}
