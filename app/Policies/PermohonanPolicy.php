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
}
