<?php

namespace App\Enums;

enum FormType: string
{
    case NORMAL  = 'normal';
    case RANGKAP = 'rangkap';

    public function label(): string
    {
        return match ($this) {
            self::NORMAL  => 'Normal',
            self::RANGKAP => 'Rangkap Jabatan',
        };
    }

    /** Form rangkap memerlukan approval tambahan dari Dirut */
    public function requiresDirut(): bool
    {
        return $this === self::RANGKAP;
    }
}
