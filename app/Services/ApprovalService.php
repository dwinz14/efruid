<?php

namespace App\Services;

use App\Enums\AksiAudit;
use App\Enums\FormType;
use App\Enums\StatusPermohonan;
use App\Models\ApprovalLog;
use App\Models\Permohonan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class ApprovalService
{

    public function __construct(private NotificationService $notifService) {}

    // ── Approve oleh Atasan ───────────────────────────────────────────────

    public function approveAtasan(Permohonan $permohonan, User $approver): void
    {
        $statusDari = $permohonan->status;

        // Tentukan status berikutnya
        $statusKe = $permohonan->requiresDirut()
            ? StatusPermohonan::PENDING_DIRUT
            : StatusPermohonan::PENDING_IT;

        // Embed TTD atasan
        $ttdPath = $this->embedSignature($approver, $permohonan, 'atasan');

        // Generate verification stamp
        $stamp = $this->generateStamp($approver, 'Atasan', $permohonan);

        // Update permohonan
        $stamps   = $permohonan->verification_stamps ?? [];
        $stamps[] = $stamp;

        $permohonan->update([
            'status'           => $statusKe,
            'ttd_atasan_path'  => $ttdPath,
            'verification_stamps' => $stamps,
        ]);

        // Catat approval log
        ApprovalLog::create([
            'permohonan_id' => $permohonan->id,
            'user_id'       => $approver->id,
            'aksi'          => 'approved',
            'status_dari'   => $statusDari->value,
            'status_ke'     => $statusKe->value,
            'ip_address'    => request()->ip(),
        ]);

        AuditService::log(
            AksiAudit::PERMOHONAN_APPROVED,
            $approver->id,
            $permohonan,
            ['status' => $statusDari->value],
            ['status' => $statusKe->value],
            $permohonan->nomor_dokumen,
        );

        // Notifikasi berdasarkan status berikutnya
        $fresh = $permohonan->fresh();
        if ($fresh->status->value === 'PENDING_DIRUT') {
            $this->notifService->notifyApprovedToDirut($fresh);
        } else {
            $this->notifService->notifyApprovedToIt($fresh);
        }
    }

    // ── Approve oleh Dirut ────────────────────────────────────────────────

    public function approveDirut(Permohonan $permohonan, User $approver): void
    {
        $statusDari = $permohonan->status;
        $statusKe   = StatusPermohonan::PENDING_IT;

        $ttdPath = $this->embedSignature($approver, $permohonan, 'dirut');
        $stamp   = $this->generateStamp($approver, 'Direktur Utama', $permohonan);

        $stamps   = $permohonan->verification_stamps ?? [];
        $stamps[] = $stamp;

        $permohonan->update([
            'status'              => $statusKe,
            'ttd_dirut_path'      => $ttdPath,
            'verification_stamps' => $stamps,
        ]);

        ApprovalLog::create([
            'permohonan_id' => $permohonan->id,
            'user_id'       => $approver->id,
            'aksi'          => 'approved',
            'status_dari'   => $statusDari->value,
            'status_ke'     => $statusKe->value,
            'ip_address'    => request()->ip(),
        ]);

        AuditService::log(
            AksiAudit::PERMOHONAN_APPROVED,
            $approver->id,
            $permohonan,
            ['status' => $statusDari->value],
            ['status' => $statusKe->value],
            $permohonan->nomor_dokumen,
        );

        //notifikasi
        $this->notifService->notifyDirutApprovedToIt($permohonan->fresh());
    }

    // ── Reject ────────────────────────────────────────────────────────────

    public function reject(Permohonan $permohonan, User $approver, string $alasan): void
    {
        $statusDari = $permohonan->status;

        $permohonan->update([
            'status'       => StatusPermohonan::REJECTED,
            'alasan_reject' => $alasan,
        ]);

        ApprovalLog::create([
            'permohonan_id' => $permohonan->id,
            'user_id'       => $approver->id,
            'aksi'          => 'rejected',
            'status_dari'   => $statusDari->value,
            'status_ke'     => StatusPermohonan::REJECTED->value,
            'catatan'       => $alasan,
            'ip_address'    => request()->ip(),
        ]);

        AuditService::log(
            AksiAudit::PERMOHONAN_REJECTED,
            $approver->id,
            $permohonan,
            ['status' => $statusDari->value],
            ['status' => StatusPermohonan::REJECTED->value, 'alasan' => $alasan],
            $permohonan->nomor_dokumen,
        );

        //notifikasi
        $this->notifService->notifyRejected($permohonan->fresh());
    }

    // ── Revisi (Pemohon pilih revisi setelah reject) ──────────────────────

    public function revise(Permohonan $permohonan, User $pemohon): void
    {
        $statusDari = $permohonan->status;

        // Reset approval chain
        $permohonan->update([
            'status'              => StatusPermohonan::DRAFT,
            'alasan_reject'       => null,
            'ttd_atasan_path'     => null,
            'ttd_dirut_path'      => null,
            'verification_stamps' => null,
            'nomor_dokumen'       => null,
            'revision_count'      => $permohonan->revision_count + 1,
        ]);

        ApprovalLog::create([
            'permohonan_id' => $permohonan->id,
            'user_id'       => $pemohon->id,
            'aksi'          => 'revised',
            'status_dari'   => $statusDari->value,
            'status_ke'     => StatusPermohonan::DRAFT->value,
            'ip_address'    => request()->ip(),
        ]);

        AuditService::log(
            AksiAudit::PERMOHONAN_REVISED,
            $pemohon->id,
            $permohonan,
            ['status' => $statusDari->value],
            ['status' => StatusPermohonan::DRAFT->value],
            null,
        );
    }

    // ── Embed TTD: copy file signature approver ke folder permohonan ──────

    private function embedSignature(User $approver, Permohonan $permohonan, string $role): ?string
    {
        if (! $approver->signature_path) {
            return null;
        }

        $src  = $approver->signature_path;
        $dest = "signatures/snapshots/{$permohonan->id}_{$role}.png";

        if (Storage::exists($src)) {
            Storage::copy($src, $dest);
            return $dest;
        }

        return null;
    }

    // ── Generate verification stamp ───────────────────────────────────────

    private function generateStamp(User $approver, string $roleLabel, Permohonan $permohonan): array
    {
        $timestamp = Carbon::now()->setTimezone('Asia/Jakarta')->format('d/m/Y H:i:s') . ' WIB';

        // Hash sederhana untuk integrity check
        $hashInput = implode('|', [
            $permohonan->nomor_dokumen ?? $permohonan->id,
            $approver->id,
            $roleLabel,
            $timestamp,
        ]);

        return [
            'role'      => $roleLabel,
            'nama'      => $approver->name,
            'jabatan'   => $approver->jabatan_label,
            'timestamp' => $timestamp,
            'hash'      => hash('sha256', $hashInput),
        ];
    }
}
