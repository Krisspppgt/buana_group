<?php

namespace App\Models;

use App\Enums\Unit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Expense extends Model
{
    protected $fillable = [
        'unit',
        'category',
        'title',
        'amount',
        'incurred_at',
        'note',
        'recorded_by',
    ];

    protected function casts(): array
    {
        return [
            'unit' => Unit::class,
            'amount' => 'integer',
            'incurred_at' => 'date',
        ];
    }

    /**
     * Daftar kategori biaya operasional & kerugian standar.
     *
     * @return array<string, string>
     */
    public static function categories(): array
    {
        return [
            'operasional' => 'Operasional & Listrik/Air',
            'pengiriman' => 'Logistik & Pengiriman',
            'kerusakan' => 'Barang Rusak / Kedaluwarsa',
            'gaji' => 'Tenaga Kerja / Lembur',
            'pemasaran' => 'Promosi & Pemasaran',
            'lainnya' => 'Pengeluaran Lainnya',
        ];
    }

    public function recorder(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}
