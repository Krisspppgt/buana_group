<?php

namespace App\Models;

use App\Enums\OrderStatus;
use App\Enums\Unit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'code', 'user_id', 'unit', 'status', 'subtotal', 'ship_fee',
        'points_redeemed', 'total', 'points_earned',
        'ship_name', 'ship_phone', 'ship_addr', 'note', 'send_at',
    ];

    protected function casts(): array
    {
        return [
            'unit' => Unit::class,
            'status' => OrderStatus::class,
            'send_at' => 'datetime',
        ];
    }

    /** Kode 12 karakter: BN + yymmdd + 4 digit urut. Contoh: BN2611170001 */
    public static function newCode(): string
    {
        $prefix = 'BN'.now()->format('ymd');
        $n = static::where('code', 'like', $prefix.'%')->count() + 1;

        return $prefix.str_pad((string) $n, 4, '0', STR_PAD_LEFT);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function productionLogs(): HasMany
    {
        return $this->hasMany(ProductionLog::class);
    }
}
