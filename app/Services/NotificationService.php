<?php

namespace App\Services;

use App\Enums\RoleUser;
use App\Enums\StatusPermohonan;
use App\Models\Permohonan;
use App\Models\User;
use App\Notifications\PermohonanNotification;
use Illuminate\Support\Facades\Notification;

class NotificationService
{
    // ── Submit → notif ke Atasan ──────────────────────────────────────────

    public function notifySubmit(Permohonan $permohonan): void
    {
        $atasan = User::find($permohonan->atasan_id);
        if (! $atasan) return;

        $atasan->notify(new PermohonanNotification(
            $permohonan,
            'permohonan_submitted',
            "Permohonan baru dari {$permohonan->nama_pemohon} menunggu persetujuan Anda.",
        ));
    }

    // ── Atasan approve normal → notif ke IT Staff & Super Admin ──────────

    public function notifyApprovedToIt(Permohonan $permohonan): void
    {
        $penerima = $this->getItStaffAndAdmin();

        Notification::send($penerima, new PermohonanNotification(
            $permohonan,
            'permohonan_ready_it',
            "Permohonan {$permohonan->nomor_dokumen} ({$permohonan->nama_pemohon}) siap dieksekusi.",
        ));
    }

    // ── Atasan approve rangkap → notif ke Dirut ──────────────────────────

    public function notifyApprovedToDirut(Permohonan $permohonan): void
    {
        $diruts = User::whereHas(
            'roles',
            fn($q) =>
            $q->where('name', RoleUser::DIRUT->value)
        )->where('is_active', true)->get();

        Notification::send($diruts, new PermohonanNotification(
            $permohonan,
            'permohonan_need_dirut',
            "Permohonan rangkap jabatan dari {$permohonan->nama_pemohon} menunggu persetujuan Direktur.",
        ));
    }

    // ── Dirut approve → notif ke IT Staff & Super Admin ──────────────────

    public function notifyDirutApprovedToIt(Permohonan $permohonan): void
    {
        $penerima = $this->getItStaffAndAdmin();

        Notification::send($penerima, new PermohonanNotification(
            $permohonan,
            'permohonan_ready_it',
            "Permohonan rangkap {$permohonan->nomor_dokumen} ({$permohonan->nama_pemohon}) siap dieksekusi.",
        ));
    }

    // ── Reject → notif ke Pemohon ─────────────────────────────────────────

    public function notifyRejected(Permohonan $permohonan): void
    {
        $pemohon = User::find($permohonan->pemohon_id);
        if (! $pemohon) return;

        $pemohon->notify(new PermohonanNotification(
            $permohonan,
            'permohonan_rejected',
            "Permohonan {$permohonan->nomor_dokumen} Anda ditolak. Periksa detail untuk alasannya.",
        ));
    }

    // ── Executed → notif ke Pemohon ───────────────────────────────────────

    public function notifyExecuted(Permohonan $permohonan): void
    {
        $pemohon = User::find($permohonan->pemohon_id);
        if (! $pemohon) return;

        $pemohon->notify(new PermohonanNotification(
            $permohonan,
            'permohonan_executed',
            "Permohonan {$permohonan->nomor_dokumen} Anda telah selesai dieksekusi. PDF tersedia untuk didownload.",
        ));
    }

    // ── URL tujuan saat notifikasi diklik (sadar role & status terkini) ───

    public function resolveUrl(User $user, array $data, ?Permohonan $permohonan): string
    {
        // Permohonan sudah dihapus / tidak ditemukan
        if (! $permohonan) {
            return route('dashboard');
        }

        $status = $permohonan->status;

        return match ($data['type'] ?? null) {
            'permohonan_submitted' => $this->urlSebagaiAtasan($user, $permohonan),

            'permohonan_need_dirut' => $status === StatusPermohonan::PENDING_DIRUT
                ? route('approval.dirut.show', $permohonan)
                : route('approval.dirut.riwayat'),

            'permohonan_ready_it' => in_array($status, [StatusPermohonan::PENDING_IT, StatusPermohonan::EXECUTED], true)
                ? route('eksekusi.show', $permohonan)
                : route('eksekusi.index'),

            'permohonan_rejected',
            'permohonan_executed' => route('permohonan.show', $permohonan),

            default => route('dashboard'),
        };
    }

    private function urlSebagaiAtasan(User $user, Permohonan $permohonan): string
    {
        $masihMenunggu = $permohonan->status === StatusPermohonan::PENDING_ATASAN
            && $permohonan->atasan_id === $user->id;

        if ($user->isDirut()) {
            return $masihMenunggu
                ? route('approval.dirut.show', $permohonan)
                : route('approval.dirut.riwayat');
        }

        return $masihMenunggu
            ? route('approval.atasan.show', $permohonan)
            : route('approval.atasan.riwayat');
    }

    // ── Helper ────────────────────────────────────────────────────────────

    private function getItStaffAndAdmin()
    {
        return User::whereHas(
            'roles',
            fn($q) =>
            $q->whereIn('name', [RoleUser::IT_STAFF->value, RoleUser::SUPER_ADMIN->value])
        )->where('is_active', true)->get();
    }
}
