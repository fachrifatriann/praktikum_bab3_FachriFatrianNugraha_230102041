<?php
// app/Enums/ProductStatus.php
namespace App\Enums;

enum ProductStatus: string
{
    case Active   = 'active';
    case Inactive = 'inactive';
    case Draft    = 'draft';
    case OutOfStock = 'out_of_stock'; // 1. Menambahkan case baru

    // Label untuk tampil di UI
    public function label(): string
    {
        return match ($this) {
            self::Active   => 'Aktif',
            self::Inactive => 'Nonaktif',
            self::Draft    => 'Draft',
            self::OutOfStock => 'Stok Habis', // 2. Menambahkan label UI
        };
    }

    // Warna Bootstrap badge
    public function color(): string
    {
        return match ($this) {
            self::Active   => 'success',
            self::Inactive => 'secondary',
            self::Draft    => 'warning',
            self::OutOfStock => 'danger', // 3. Menambahkan warna badge (Merah)
        };
    }

    // Icon Bootstrap Icons
    public function icon(): string
    {
        return match ($this) {
            self::Active   => 'bi-check-circle-fill',
            self::Inactive => 'bi-x-circle-fill',
            self::Draft    => 'bi-pencil-fill',
            self::OutOfStock => 'bi-exclamation-triangle-fill', // 4. Menambahkan icon peringatan
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
