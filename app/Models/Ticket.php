<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'code', 'user_id', 'category', 'title', 'passenger_name', 'passenger_id',
        'schedule_at', 'seat_info', 'qr_payload', 'price', 'status', 'checked_in_at',
    ];

    protected function casts(): array
    {
        return [
            'schedule_at' => 'datetime',
            'checked_in_at' => 'datetime',
        ];
    }

    public static function newCode(): string
    {
        $prefix = 'TIX'.now()->format('ymd');
        $n = static::where('code', 'like', $prefix.'%')->count() + 1;

        return $prefix.str_pad((string) $n, 4, '0', STR_PAD_LEFT);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            0 => 'Valid / Aktif',
            1 => 'Sudah Boarding / Di-scan',
            2 => 'Dibatalkan',
            default => 'Aktif',
        };
    }
}
