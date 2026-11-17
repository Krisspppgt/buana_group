<?php

namespace App\Enums;

enum QuoteStatus: int
{
    case Submitted = 0;
    case Offered = 1;
    case Approved = 2;
    case Rejected = 3;

    public function label(): string
    {
        return match ($this) {
            self::Submitted => 'Diajukan',
            self::Offered => 'Ditawarkan',
            self::Approved => 'Disetujui',
            self::Rejected => 'Ditolak',
        };
    }
}