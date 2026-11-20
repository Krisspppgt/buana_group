<?php

namespace App\Models;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Enums\Unit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class Order extends Model
{
    protected $fillable = [
        'code', 'user_id', 'unit', 'status', 'subtotal', 'ship_fee',
        'points_redeemed', 'total', 'points_earned',
        'ship_name', 'ship_phone', 'ship_addr', 'note', 'send_at',
    ];

    protected static function booted(): void
    {
        static::updated(function (Order $order): void {
            if (! $order->wasChanged('status')) {
                return;
            }

            if (! in_array($order->status, [OrderStatus::Paid, OrderStatus::Done], true)) {
                return;
            }

            $hasPendingTempo = $order->payments()
                ->where('method', 'tempo_b2b')
                ->where('status', PaymentStatus::Pending)
                ->exists();

            if (! $hasPendingTempo) {
                $order->awardLoyaltyPoints();
            }
        });
    }

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

    /**
     * Memberikan Buana Loyalty Points (1% dari total transaksi) secara idempoten.
     */
    public function awardLoyaltyPoints(string $descriptionPrefix = 'Reward Transaksi'): int
    {
        if ($this->points_earned > 0 || $this->total <= 0) {
            return 0;
        }

        return DB::transaction(function () use ($descriptionPrefix): int {
            $freshOrder = static::query()->lockForUpdate()->find($this->id);
            if (! $freshOrder || $freshOrder->points_earned > 0 || $freshOrder->total <= 0) {
                return 0;
            }

            $user = $freshOrder->user;
            if (! $user) {
                return 0;
            }

            $earned = $user->earnLoyaltyPoints(
                (int) $freshOrder->total,
                $freshOrder->unit,
                $freshOrder->code,
                "{$descriptionPrefix} {$freshOrder->unit->label()} #{$freshOrder->code}"
            );

            if ($earned > 0) {
                $freshOrder->points_earned = $earned;
                $freshOrder->saveQuietly();
                $this->points_earned = $earned;
            }

            return $earned;
        });
    }
}
