<?php

namespace App\Enums;

enum Unit: int
{
    case Air = 1;
    case Food = 2;
    case Konveksi = 3;

    public function label(): string
    {
        return match ($this) {
            self::Air => 'Air Mineral Unwahas',
            self::Food => 'Buana Food Services',
            self::Konveksi => 'Buana Konveksi',
        };
    }
}