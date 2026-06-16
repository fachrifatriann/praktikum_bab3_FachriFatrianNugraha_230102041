<?php
// app/Enums/ProductStatus.php
namespace App\Enums;


enum ProductStatus: string
{
    case Active   = 'active';
    case Inactive = 'inactive';
    case Draft    = 'draft';


    // Label untuk tampil di UI
    public function label(): string
    {
        return match ($this) {
            self::Active   => 'Aktif',
            self::Inactive => 'Nonaktif',
            self::Draft    => 'Draft',
        };
    }


    // Warna Bootstrap badge
    public function color(): string
    {
        return match ($this) {
            self::Active   => 'success',
            self::Inactive => 'secondary',
            self::Draft    => 'warning',
        };
    }


    // Icon Bootstrap Icons
    public function icon(): string
    {
        return match ($this) {
            self::Active   => 'bi-check-circle-fill',
            self::Inactive => 'bi-x-circle-fill',
            self::Draft    => 'bi-pencil-fill',
        };
    }


    // Helper: apakah bisa dipublish?
    public function canBePublished(): bool
    {
        return $this === self::Draft || $this === self::Inactive;
    }


    // Static: semua opsi untuk <select>
    public static function options(): array
    {
        return array_map(
            fn (self $case) => ['value' => $case->value, 'label' => $case->label()],
            self::cases()
        );
    }
}
