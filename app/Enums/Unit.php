<?php

namespace App\Enums;

enum Unit: int
{
    case Air = 1;
    case Food = 2;
    case Konveksi = 3;
    case Tour = 4;
    case Ticketing = 5;

    public function label(): string
    {
        return match ($this) {
            self::Air => 'Air Mineral Unwahas',
            self::Food => 'Buana Food Services',
            self::Konveksi => 'Buana Konveksi',
            self::Tour => 'Buana Tour & Travel',
            self::Ticketing => 'Buana Ticketing',
        };
    }
}
