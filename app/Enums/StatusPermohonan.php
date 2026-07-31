<?php

namespace App\Enums;

enum StatusPermohonan: string
{
    case DRAFT            = 'DRAFT';
    case PENDING_ATASAN   = 'PENDING_ATASAN';
    case PENDING_DIRUT    = 'PENDING_DIRUT';
    case PENDING_IT       = 'PENDING_IT';
    case EXECUTED         = 'EXECUTED';
    case REJECTED         = 'REJECTED';
    case CANCELLED        = 'CANCELLED';

    public function label(): string
    {
        return match ($this) {
            self::DRAFT          => 'Draft',
            self::PENDING_ATASAN => 'Menunggu Atasan',
            self::PENDING_DIRUT  => 'Menunggu Direktur',
            self::PENDING_IT     => 'Menunggu Eksekusi IT',
            self::EXECUTED       => 'Selesai Dieksekusi',
            self::REJECTED       => 'Ditolak',
            self::CANCELLED      => 'Dibatalkan',
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::DRAFT          => 'badge-draft',
            self::PENDING_ATASAN,
            self::PENDING_DIRUT,
            self::PENDING_IT     => 'badge-pending',
            self::EXECUTED       => 'badge-executed',
            self::REJECTED       => 'badge-rejected',
            self::CANCELLED      => 'badge-cancelled',
        };
    }

    /** Status yang masih bisa dibatalkan oleh Pemohon */
    public function cancellable(): bool
    {
        return in_array($this, [self::DRAFT, self::PENDING_ATASAN, self::PENDING_DIRUT]);
    }

    /** Status yang bisa direvisi oleh Pemohon */
    public function revisable(): bool
    {
        return $this === self::REJECTED;
    }
}
