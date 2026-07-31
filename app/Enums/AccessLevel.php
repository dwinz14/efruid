<?php

namespace App\Enums;

enum AccessLevel: string
{
    case DIREKSI      = 'DIREKSI';
    case ADMINISTRATOR = 'ADMINISTRATOR';
    case USER          = 'USER';

    public function label(): string
    {
        return match ($this) {
            self::DIREKSI       => 'Direksi',
            self::ADMINISTRATOR => 'Administrator',
            self::USER          => 'User',
        };
    }
}
