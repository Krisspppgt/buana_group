<?php

namespace App\Enums;

enum ProductionStage: int
{
    case WaitingMaterial = 1;
    case Cutting = 2;
    case Sewing = 3;
    case Printing = 4;
    case Finishing = 5;
    case ReadyToShip = 6;

    public function label(): string
    {
        return match ($this) {
            self::WaitingMaterial => 'Menunggu Bahan',
            self::Cutting => 'Potong',
            self::Sewing => 'Jahit',
            self::Printing => 'Sablon/Bordir',
            self::Finishing => 'Finishing',
            self::ReadyToShip => 'Siap Kirim',
        };
    }
}
