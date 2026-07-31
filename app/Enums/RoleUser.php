<?php

namespace App\Enums;

enum RoleUser: string
{
    case SUPER_ADMIN = 'super_admin';
    case PEMOHON     = 'pemohon';
    case ATASAN      = 'atasan';
    case DIRUT       = 'dirut';
    case IT_STAFF    = 'it_staff';

    public function label(): string
    {
        return match ($this) {
            self::SUPER_ADMIN => 'Super Admin',
            self::PEMOHON     => 'Pemohon',
            self::ATASAN      => 'Atasan',
            self::DIRUT       => 'Direktur Utama',
            self::IT_STAFF    => 'Staff IT',
        };
    }
}
