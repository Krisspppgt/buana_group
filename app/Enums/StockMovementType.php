<?php

namespace App\Enums;

enum StockMovementType: string
{
    case In = 'in';
    case Out = 'out';
    case Adjustment = 'adjustment';

    public function label(): string
    {
        return match ($this) {
            self::In => 'Stok Masuk',
            self::Out => 'Stok Keluar',
            self::Adjustment => 'Penyesuaian',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::In => 'success',
            self::Out => 'danger',
            self::Adjustment => 'warning',
        };
    }
}
