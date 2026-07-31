<?php

namespace App\Enums;

enum TipePerubahan: string
{
    case PERMANEN  = 'permanen';
    case SEMENTARA = 'sementara';

    public function label(): string
    {
        return match ($this) {
            self::PERMANEN  => 'Permanen',
            self::SEMENTARA => 'Sementara',
        };
    }
}
