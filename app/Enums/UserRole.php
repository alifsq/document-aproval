<?php
namespace App\Enums;

enum UserRole: string
{
    case ADMIN = 'admin';
    case MANAGER = 'manager';
    case STAFF = 'staff';
    case AUDITOR = 'auditor';

    public function label()
    {
        return match ($this) {
            self::ADMIN => 'admin',
            self::MANAGER => 'manager',
            self::STAFF => 'staff',
            self::AUDITOR => 'auditor',
        };
    }
}
