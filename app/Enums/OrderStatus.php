<?php

namespace App\Enums;

enum OrderStatus: int
{
    case Pending = 0;     // menunggu bayar
    case Paid = 1;
    case Processing = 2;
    case Shipped = 3;
    case Done = 4;
    case Cancelled = 9;

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Menunggu Pembayaran',
            self::Paid => 'Dibayar',
            self::Processing => 'Diproses',
            self::Shipped => 'Dikirim',
            self::Done => 'Selesai',
            self::Cancelled => 'Dibatalkan',
        };
    }
}