<?php

namespace App\Policies;

use App\Models\Permohonan;
use App\Models\User;
use App\Enums\StatusPermohonan;
use Illuminate\Auth\Access\Response;

class PermohonanPolicy
{
    /**
     * Super admin bypass semua policy.
     */
    public function before(User $user): ?bool
    {
        return $user->isSuperAdmin() ? true : null;
    }

    /** Lihat detail permohonan */
    public function view(User $user, Permohonan $permohonan): bool
    {
        return $user->id === $permohonan->pemohon_id;
    }

    /** Edit draft */
    public function update(User $user, Permohonan $permohonan): bool
    {
        return $user->id === $permohonan->pemohon_id
            && $permohonan->status === StatusPermohonan::DRAFT;
    }

    /** Submit permohonan */
    public function submit(User $user, Permohonan $permohonan): bool
    {
        return $user->id === $permohonan->pemohon_id
            && $permohonan->status === StatusPermohonan::DRAFT;
    }

    /** Batalkan permohonan */
    public function cancel(User $user, Permohonan $permohonan): bool
    {
        return $user->id === $permohonan->pemohon_id
            && $permohonan->isCancellable();
    }

    // download pdf khusus admin
    public function download(User $user, Permohonan $permohonan): bool
    {
        return $user->isItStaff();
    }

    // IT Staff tidak boleh mengeksekusi permohonan yang ia ajukan sendiri (conflict of interest).
    public function claim(User $user, Permohonan $permohonan): bool
    {
        return $permohonan->pemohon_id !== $user->id
            && $permohonan->status === StatusPermohonan::PENDING_IT
            && ! $permohonan->isClaimed()
            && $user->isItStaff();
    }

    public function execute(User $user, Permohonan $permohonan): bool
    {
        return $permohonan->pemohon_id !== $user->id
            && $permohonan->status === StatusPermohonan::PENDING_IT
            && $permohonan->isClaimedBy($user->id)
            && $user->isItStaff();
    }
}
