<?php
namespace App\Enums;

use App\Models\Document;
use App\Models\User;

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


    private function isOneOf(self ...$roles): bool
    {
        return in_array($this, $roles, true);
    }
    public function canCreate()
    {
        return $this->isOneOf(self::STAFF, self::MANAGER);
    }

    public function canEdit(Document $document, User $user)
    {
        return $this === self::STAFF
            && $document->created_by === $user->id
            && $document->status === DocumentStatus::DRAFT;

    }

    public function canSubmit(Document $document, User $user)
    {
        return $this === self::STAFF
            && $document->created_by === $user->id
            && $document->status === DocumentStatus::DRAFT;

    }
    public function canApprove(Document $document, User $user)
    {
        return $this === self::MANAGER
            && $document->created_by === $user->id
            && $document->status === DocumentStatus::SUBMITTED;
    }
    public function canView(Document $document, User $user)
    {
        return match ($this) {
                // Admin & Manager: lihat semua dokumen dalam tenantnya
            self::ADMIN, self::MANAGER =>
                    $document->tenant_id === $user->tenant_id,

                // Staff: hanya dokumen miliknya sendiri
            self::STAFF =>
                    $document->created_by === $user->id
                    && $document->tenant_id === $user->tenant_id,

                // Auditor: hanya dokumen status approved dalam tenant yang sama
            self::AUDITOR =>
                    $document->status === DocumentStatus::APPROVED
                    && $document->tenant_id === $user->tenant_id,
        };
    }

}
