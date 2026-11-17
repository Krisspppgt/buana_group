<?php

namespace App\Enums;

enum Role: int
{
    case Customer = 1;      // Pelanggan Umum
    case Civitas = 2;       // Mahasiswa / Dosen / Pegawai Yayasan
    case Coordinator = 3;   // Koordinator B2B / Panitia / Rombongan
    case Courier = 4;       // Kurir / Driver Armada
    case Operator = 5;      // Operator Produksi / Scanner Tiket
    case Admin = 6;         // Admin Unit Usaha
    case SuperAdmin = 7;    // Super Admin PT Buana Sejahtera / Yayasan

    public function label(): string
    {
        return match ($this) {
            self::Customer => 'Pelanggan Umum',
            self::Civitas => 'Civitas Akademika',
            self::Coordinator => 'Koordinator / B2B',
            self::Courier => 'Kurir Logistik',
            self::Operator => 'Operator Lapangan',
            self::Admin => 'Admin Unit Usaha',
            self::SuperAdmin => 'Super Admin',
        };
    }
}
