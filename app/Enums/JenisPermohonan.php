<?php

namespace App\Enums;

enum JenisPermohonan: string
{
    case PENDAFTARAN = 'pendaftaran';
    case PERUBAHAN   = 'perubahan';
    case NONAKTIF    = 'nonaktif';

    public function label(): string
    {
        return match($this) {
            self::PENDAFTARAN => 'Pendaftaran',
            self::PERUBAHAN   => 'Perubahan',
            self::NONAKTIF    => 'Non-Aktifkan',
        };
    }
}
