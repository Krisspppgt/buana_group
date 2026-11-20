<?php

namespace App\Enums;

enum VerificationStatus: int
{
    case None = 0;
    case Pending = 1;
    case Approved = 2;
    case Rejected = 3;

    public function label(): string
    {
        return match ($this) {
            self::None => 'Reguler',
            self::Pending => 'Menunggu Verifikasi',
            self::Approved => 'Terverifikasi',
            self::Rejected => 'Ditolak',
        };
    }
}
