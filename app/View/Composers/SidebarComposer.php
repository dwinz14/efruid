<?php

namespace App\View\Composers;

use App\Enums\StatusPermohonan;
use App\Models\Permohonan;
use App\Models\User;
use Illuminate\View\View;

class SidebarComposer
{
    public function compose(View $view): void
    {
        $view->with('navCounts', $this->counts(auth()->user()));
    }

    /**
     * Jumlah pekerjaan yang menunggu tindakan user, per menu sidebar.
     */
    public function counts(?User $user): array
    {
        $counts = ['permohonan' => 0, 'atasan' => 0, 'dirut' => 0, 'eksekusi' => 0, 'users' => 0];

        if (! $user) {
            return $counts;
        }

        // Permohonan sendiri yang ditolak (perlu direvisi atau dibatalkan)
        $counts['permohonan'] = Permohonan::where('pemohon_id', $user->id)
            ->where('status', StatusPermohonan::REJECTED)
            ->count();

        // ApprovalController::atasanIndex()
        if ($user->isAtasan()) {
            $counts['atasan'] = Permohonan::where('atasan_id', $user->id)
                ->where('status', StatusPermohonan::PENDING_ATASAN)
                ->count();
        }

        // ApprovalController::dirutIndex(): sebagai atasan + menunggu Direktur
        if ($user->isDirut()) {
            $counts['dirut'] = Permohonan::where(function ($q) use ($user) {
                $q->where('status', StatusPermohonan::PENDING_DIRUT)
                    ->orWhere(fn($q) => $q
                        ->where('status', StatusPermohonan::PENDING_ATASAN)
                        ->where('atasan_id', $user->id));
            })->count();
        }

        // EksekusiController::index(): belum diklaim, atau diklaim oleh user ini
        if ($user->isItStaff()) {
            $counts['eksekusi'] = Permohonan::where('status', StatusPermohonan::PENDING_IT)
                ->where(fn($q) => $q->whereNull('executor_id')->orWhere('executor_id', $user->id))
                ->count();
        }

        // Registrasi yang menunggu verifikasi admin
        if ($user->isSuperAdmin()) {
            $counts['users'] = User::where('email_verified', false)
                ->where('is_active', true)
                ->count();
        }

        return $counts;
    }
}
