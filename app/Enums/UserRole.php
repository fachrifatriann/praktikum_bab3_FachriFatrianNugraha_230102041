<?php
// app/Enums/UserRole.php
namespace App\Enums;


enum UserRole: string
{
    case Admin    = 'admin';
    case Manager  = 'manager';
    case Staff    = 'staff';
    case Customer = 'customer';


    public function label(): string
    {
        return match ($this) {
            self::Admin    => 'Administrator',
            self::Manager  => 'Manager',
            self::Staff    => 'Staff',
            self::Customer => 'Customer',
        };
    }


    public function color(): string
    {
        return match ($this) {
            self::Admin    => 'danger',
            self::Manager  => 'warning',
            self::Staff    => 'info',
            self::Customer => 'secondary',
        };
    }


    // ── Permissions level (semakin tinggi = lebih banyak akses)


    public function level(): int
    {
        return match ($this) {
            self::Admin    => 4,
            self::Manager  => 3,
            self::Staff    => 2,
            self::Customer => 1,
        };
    }


    public function canManageProducts(): bool
    {
        return $this->level() >= 3;
    }


    public function canManageUsers(): bool
    {
        return $this === self::Admin;
    }


    public function canViewReports(): bool
    {
        return $this->level() >= 3;
    }
}
