<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TourBooking extends Model
{
    use HasFactory;

    protected $fillable = [
        'code', 'user_id', 'tour_package_id', 'coordinator_name', 'coordinator_phone',
        'total_pax', 'payment_scheme', 'installment_tenor', 'paid_amount',
        'total_amount', 'members_data', 'manifest_file', 'status',
    ];

    protected function casts(): array
    {
        return [
            'members_data' => 'array',
        ];
    }

    public static function newCode(): string
    {
        $prefix = 'TR'.now()->format('ymd');
        $n = static::where('code', 'like', $prefix.'%')->count() + 1;

        return $prefix.str_pad((string) $n, 4, '0', STR_PAD_LEFT);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function tourPackage(): BelongsTo
    {
        return $this->belongsTo(TourPackage::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            0 => 'Menunggu Konfirmasi',
            1 => 'Cicilan Aktif',
            2 => 'Lunas / Siap Berangkat',
            3 => 'Selesai',
            9 => 'Dibatalkan',
            default => 'Diproses',
        };
    }
}
