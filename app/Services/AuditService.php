<?php

namespace App\Services;

use App\Enums\AksiAudit;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Request;

class AuditService
{
    /**
     * Catat satu aksi ke audit_logs.
     *
     * @param AksiAudit   $aksi
     * @param int|null    $userId      User yang melakukan aksi (null = sistem/guest)
     * @param object|null $subject     Model yang terdampak (opsional)
     * @param array|null  $before      State sebelum perubahan
     * @param array|null  $after       State setelah perubahan
     * @param string|null $nomorDokumen
     */
    public static function log(
        AksiAudit $aksi,
        ?int $userId = null,
        ?object $subject = null,
        ?array $before = null,
        ?array $after = null,
        ?string $nomorDokumen = null,
    ): void {
        AuditLog::create([
            'user_id'       => $userId,
            'aksi'          => $aksi,
            'subject_type'  => $subject ? get_class($subject) : null,
            'subject_id'    => $subject?->id,
            'nomor_dokumen' => $nomorDokumen,
            'before'        => $before,
            'after'         => $after,
            'ip_address'    => Request::ip(),
            'user_agent'    => Request::userAgent(),
        ]);
    }

    /**
     * Shortcut: log aksi auth (tidak ada before/after)
     */
    public static function auth(AksiAudit $aksi, int $userId, ?array $meta = null): void
    {
        static::log($aksi, $userId, null, null, $meta);
    }
}
