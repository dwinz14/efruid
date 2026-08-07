<?php

namespace App\Policies;

use App\Enums\StatusPermohonan;
use App\Models\Permohonan;
use App\Models\User;

class ApprovalPolicy
{
    public function before(User $user): ?bool
    {
        return $user->isSuperAdmin() ? true : null;
    }

    /** Atasan bisa approve permohonan yang ditujukan kepadanya */
    public function approveAsAtasan(User $user, Permohonan $permohonan): bool
    {
        return $user->isAtasan()
            && $permohonan->status === StatusPermohonan::PENDING_ATASAN
            && $permohonan->atasan_id === $user->id;
    }

    /** Dirut bisa approve permohonan rangkap yang sudah di-approve atasan */
    public function approveAsDirut(User $user, Permohonan $permohonan): bool
    {
        return $user->isDirut()
            && $permohonan->status === StatusPermohonan::PENDING_DIRUT;
    }

    /** Atasan atau Dirut bisa reject sesuai status saat ini */
    public function reject(User $user, Permohonan $permohonan): bool
    {
        if ($permohonan->status === StatusPermohonan::PENDING_ATASAN) {
            return $user->isAtasan() && $permohonan->atasan_id === $user->id;
        }

        if ($permohonan->status === StatusPermohonan::PENDING_DIRUT) {
            return $user->isDirut();
        }

        return false;
    }

    /** Pemohon bisa revisi setelah reject */
    public function revise(User $user, Permohonan $permohonan): bool
    {
        return $permohonan->pemohon_id === $user->id
            && $permohonan->status === StatusPermohonan::REJECTED;
    }
}
