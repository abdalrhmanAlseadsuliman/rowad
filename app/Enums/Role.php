<?php

namespace App\Enums;

enum Role: string
{
    case Admin = 'admin';
    case Student = 'student';

    public function getLabel(): string
    {
        return match ($this) {
            self::Admin => 'مدير',
            self::Student => 'طالب',
        };
    }
}
