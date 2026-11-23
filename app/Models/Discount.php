<?php

namespace App\Models;

use App\Enums\DiscountType;
use App\Enums\Unit;
use Database\Factories\DiscountFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Discount extends Model
{
    /** @use HasFactory<DiscountFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'description',
        'type',
        'value',
        'max_discount',
        'min_subtotal',
        'unit',
        'internal_only',
        'quota',
        'used_count',
        'max_uses_per_user',
        'starts_at',
        'expires_at',
        'is_active',
    ];

    protected static function booted(): void
    {
        static::saving(function (Discount $discount): void {
            if (is_string($discount->code)) {
                $discount->code = strtoupper(trim($discount->code));
            }
        });
    }

    protected function casts(): array
    {
        return [
            'type' => DiscountType::class,
            'unit' => Unit::class,
            'internal_only' => 'boolean',
            'value' => 'integer',
            'max_discount' => 'integer',
            'min_subtotal' => 'integer',
            'quota' => 'integer',
            'used_count' => 'integer',
            'max_uses_per_user' => 'integer',
            'starts_at' => 'datetime',
            'expires_at' => 'datetime',
            'is_active' => 'boolean',
        ];
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Filter diskon yang aktif, masih dalam masa berlaku, dan kuotanya masih tersedia.
     *
     * @param  Builder<Discount>  $query
     * @return Builder<Discount>
     */
    public function scopeCurrentlyAvailable(Builder $query): Builder
    {
        $now = now();

        return $query
            ->where('is_active', true)
            ->where(function (Builder $q) use ($now): void {
                $q->whereNull('starts_at')->orWhere('starts_at', '<=', $now);
            })
            ->where(function (Builder $q) use ($now): void {
                $q->whereNull('expires_at')->orWhere('expires_at', '>=', $now);
            })
            ->where(function (Builder $q): void {
                $q->whereNull('quota')->orWhereColumn('used_count', '<', 'quota');
            });
    }

    /**
     * Menghitung nominal potongan diskon berdasarkan subtotal pesanan.
     */
    public function calculateAmount(int $subtotal): int
    {
        if ($subtotal <= 0) {
            return 0;
        }

        if ($this->type === DiscountType::Percentage) {
            $percent = min(100, max(0, (int) $this->value));
            $amount = (int) floor(($subtotal * $percent) / 100);

            if ($this->max_discount !== null && $this->max_discount > 0) {
                $amount = min($amount, (int) $this->max_discount);
            }
        } else {
            $amount = max(0, (int) $this->value);
        }

        return max(0, min($subtotal, $amount));
    }

    public function remainingQuota(): ?int
    {
        if ($this->quota === null) {
            return null;
        }

        return max(0, $this->quota - $this->used_count);
    }

    public function formattedValue(): string
    {
        if ($this->type === DiscountType::Percentage) {
            $label = "{$this->value}%";
            if ($this->max_discount !== null && $this->max_discount > 0) {
                $label .= ' (maks. Rp '.number_format($this->max_discount, 0, ',', '.').')';
            }

            return $label;
        }

        return 'Rp '.number_format($this->value, 0, ',', '.');
    }
}
