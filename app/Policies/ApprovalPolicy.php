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

    /**
     * Approve dari PENDING_ATASAN — berdasarkan atasan_id, bukan role.
     * Memungkinkan Dirut, Pimcab, Kasie approve tanpa harus punya role ATASAN.
     */
    public function approveAsAtasan(User $user, Permohonan $permohonan): bool
    {
        return $permohonan->status === StatusPermohonan::PENDING_ATASAN
            && $permohonan->atasan_id === $user->id
            && $user->is_active;
    }

    /**
     * Approve dari PENDING_DIRUT — hanya Dirut berdasarkan role.
     */
    public function approveAsDirut(User $user, Permohonan $permohonan): bool
    {
        return $user->isDirut()
            && $permohonan->status === StatusPermohonan::PENDING_DIRUT
            && $user->is_active;
    }

    /**
     * Reject — PENDING_ATASAN: cek atasan_id. PENDING_DIRUT: cek role Dirut.
     */
    public function reject(User $user, Permohonan $permohonan): bool
    {
        if ($permohonan->status === StatusPermohonan::PENDING_ATASAN) {
            return $permohonan->atasan_id === $user->id && $user->is_active;
        }

        if ($permohonan->status === StatusPermohonan::PENDING_DIRUT) {
            return $user->isDirut() && $user->is_active;
        }

        return false;
    }

    /**
     * Revisi — hanya pemohon sendiri setelah REJECTED.
     */
    public function revise(User $user, Permohonan $permohonan): bool
    {
        return $permohonan->pemohon_id === $user->id
            && $permohonan->status === StatusPermohonan::REJECTED;
    }
}
