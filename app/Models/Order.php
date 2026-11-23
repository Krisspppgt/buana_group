<?php

namespace App\Models;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Enums\Unit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class Order extends Model
{
    protected $fillable = [
        'code', 'user_id', 'unit', 'status', 'subtotal', 'ship_fee',
        'discount_id', 'discount_code', 'discount_amount',
        'points_redeemed', 'total', 'points_earned',
        'ship_name', 'ship_phone', 'ship_addr', 'note', 'send_at',
        'confirmed_at', 'admin_note',
    ];

    protected static function booted(): void
    {
        static::updated(function (Order $order): void {
            if (! $order->wasChanged('status')) {
                return;
            }

            if ($order->status === OrderStatus::Cancelled) {
                $rawOriginalStatus = $order->getRawOriginal('status');
                $previousStatus = match (true) {
                    $rawOriginalStatus instanceof OrderStatus => $rawOriginalStatus,
                    $rawOriginalStatus !== null => OrderStatus::tryFrom((int) $rawOriginalStatus),
                    default => null,
                };

                if ($previousStatus !== OrderStatus::Cancelled) {
                    $order->restoreItemStocks();
                    $order->releaseDiscountQuota();
                }

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
            'confirmed_at' => 'datetime',
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

    public function discount(): BelongsTo
    {
        return $this->belongsTo(Discount::class);
    }

    public function recalculateTotal(): void
    {
        $netSubtotal = max(0, (int) $this->subtotal - (int) $this->discount_amount - (int) $this->points_redeemed);
        $this->total = $netSubtotal + (int) $this->ship_fee;
    }

    /**
     * Mengembalikan kuota diskon yang dipakai saat pesanan dibatalkan.
     */
    public function releaseDiscountQuota(): void
    {
        $discountId = $this->discount_id ?: static::query()->whereKey($this->id)->value('discount_id');
        if (! $discountId) {
            return;
        }

        DB::transaction(function () use ($discountId): void {
            $discount = Discount::query()->lockForUpdate()->find($discountId);
            if ($discount && $discount->used_count > 0) {
                $discount->decrement('used_count');
            }
        });
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

    /**
     * Memvalidasi dan mengurangi stok produk secara atomik di dalam transaksi order.
     *
     * @param  array<int|string, int|string|null>  $selectedItems
     * @return array{
     *     items: list<array{product_id: int, qty: int, price: int}>,
     *     subtotal: int,
     *     total_qty: int,
     *     has_bulk_item: bool,
     * }
     *
     * @throws ValidationException
     */
    public static function reserveItems(User $user, Unit $unit, array $selectedItems, int $defaultBulkMin): array
    {
        $productIds = array_map('intval', array_keys($selectedItems));
        sort($productIds);

        $productModels = Product::whereIn('id', $productIds)
            ->where('unit', $unit)
            ->where('is_active', true)
            ->orderBy('id')
            ->lockForUpdate()
            ->get()
            ->keyBy('id');

        $subtotal = 0;
        $totalQty = 0;
        $hasBulkItem = false;
        $items = [];

        foreach ($selectedItems as $productId => $qty) {
            $qty = (int) $qty;
            if ($qty <= 0) {
                continue;
            }

            $product = $productModels->get((int) $productId);
            if (! $product) {
                throw ValidationException::withMessages([
                    'products' => 'Produk yang dipilih tidak tersedia atau sudah nonaktif.',
                ]);
            }

            if ($product->stock < $qty) {
                throw ValidationException::withMessages([
                    'products' => "Stok {$product->name} tidak mencukupi (tersisa {$product->stock} {$product->uom}).",
                ]);
            }

            $product->decrement('stock', $qty);

            if ($qty >= ($product->bulk_min ?: $defaultBulkMin)) {
                $hasBulkItem = true;
            }

            $unitPrice = $product->priceFor($user, $qty);
            $subtotal += ($unitPrice * $qty);
            $totalQty += $qty;

            $items[] = [
                'product_id' => $product->id,
                'qty' => $qty,
                'price' => $unitPrice,
            ];
        }

        if (empty($items)) {
            throw ValidationException::withMessages([
                'products' => 'Pilih minimal satu produk dengan jumlah lebih dari 0.',
            ]);
        }

        return [
            'items' => $items,
            'subtotal' => $subtotal,
            'total_qty' => $totalQty,
            'has_bulk_item' => $hasBulkItem,
        ];
    }

    /**
     * Mengembalikan stok produk sesuai daftar item pada order yang dibatalkan.
     */
    public function restoreItemStocks(): void
    {
        $items = $this->items()->get();
        if ($items->isEmpty()) {
            return;
        }

        DB::transaction(function () use ($items): void {
            $productIds = $items->pluck('product_id')->unique()->sort()->values()->all();
            $products = Product::whereIn('id', $productIds)
                ->orderBy('id')
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            foreach ($items as $item) {
                $product = $products->get($item->product_id);
                if ($product && $item->qty > 0) {
                    $product->increment('stock', (int) $item->qty);
                }
            }
        });
    }
}
