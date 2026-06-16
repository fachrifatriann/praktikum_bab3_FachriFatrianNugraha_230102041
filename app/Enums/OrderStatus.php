<?php
// app/Enums/OrderStatus.php
namespace App\Enums;


enum OrderStatus: string
{
    case Pending    = 'pending';
    case Confirmed  = 'confirmed';
    case Processing = 'processing';
    case Shipped    = 'shipped';
    case Delivered  = 'delivered';
    case Cancelled  = 'cancelled';
    case Refunded   = 'refunded';


    public function label(): string
    {
        return match ($this) {
            self::Pending    => 'Menunggu Konfirmasi',
            self::Confirmed  => 'Dikonfirmasi',
            self::Processing => 'Sedang Diproses',
            self::Shipped    => 'Dikirim',
            self::Delivered  => 'Diterima',
            self::Cancelled  => 'Dibatalkan',
            self::Refunded   => 'Dikembalikan',
        };
    }


    public function color(): string
    {
        return match ($this) {
            self::Pending    => 'warning',
            self::Confirmed  => 'info',
            self::Processing => 'primary',
            self::Shipped    => 'indigo',
            self::Delivered  => 'success',
            self::Cancelled  => 'danger',
            self::Refunded   => 'secondary',
        };
    }


    // ── Status berikutnya yang valid ───────────────


    public function nextStatuses(): array
    {
        return match ($this) {
            self::Pending    => [self::Confirmed, self::Cancelled],
            self::Confirmed  => [self::Processing, self::Cancelled],
            self::Processing => [self::Shipped],
            self::Shipped    => [self::Delivered],
            self::Delivered  => [],
            self::Cancelled  => [],
            self::Refunded   => [],
        };
    }


// ── Apakah status ini final (tidak bisa berubah)?


    public function isFinal(): bool
    {
        return in_array($this, [
            self::Delivered,
            self::Cancelled,
            self::Refunded,
        ]);
    }
}
