<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Models\Discount;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class DiscountService
{
    /**
     * Mengambil daftar voucher diskon yang aktif dan relevan untuk unit usaha pesanan.
     *
     * @return Collection<int, Discount>
     */
    public function availableForOrder(Order $order, User $user): Collection
    {
        $ownerId = $order->user_id ?: $user->id;

        $discounts = Discount::query()
            ->currentlyAvailable()
            ->where(function ($query) use ($order): void {
                $query->whereNull('unit')
                    ->orWhere('unit', $order->unit->value);
            })
            ->orderByDesc('id')
            ->get();

        if ($discounts->isEmpty()) {
            return $discounts;
        }

        $usageCounts = Order::query()
            ->selectRaw('discount_id, COUNT(*) as total_used')
            ->where('user_id', $ownerId)
            ->where('id', '!=', $order->id)
            ->where('status', '!=', OrderStatus::Cancelled)
            ->whereIn('discount_id', $discounts->pluck('id'))
            ->groupBy('discount_id')
            ->pluck('total_used', 'discount_id');

        return $discounts
            ->filter(function (Discount $discount) use ($usageCounts): bool {
                if ($discount->max_uses_per_user === null) {
                    return true;
                }

                $usedByUser = (int) ($usageCounts[$discount->id] ?? 0);

                return $usedByUser < $discount->max_uses_per_user;
            })
            ->sortByDesc(function (Discount $discount) use ($order): int {
                $eligible = (int) $order->subtotal >= (int) $discount->min_subtotal;

                return ($eligible ? 100_000_000 : 0) + $discount->calculateAmount((int) $order->subtotal);
            })
            ->values();
    }

    /**
     * Menentukan apakah diskon pada pesanan masih boleh ditambah/diubah/dihapus.
     */
    public function canModifyOrderDiscount(Order $order): bool
    {
        if (in_array($order->status, [
            OrderStatus::WaitingConfirmation,
            OrderStatus::Paid,
            OrderStatus::Done,
            OrderStatus::Cancelled,
        ], true)) {
            return false;
        }

        $hasBlockingPayment = $order->payments()
            ->where(function ($query): void {
                $query->where('status', PaymentStatus::Success)
                    ->orWhere(function ($tempoQuery): void {
                        $tempoQuery->where('method', 'tempo_b2b')
                            ->where('status', PaymentStatus::Pending);
                    });
            })
            ->exists();

        return ! $hasBlockingPayment;
    }

    /**
     * Memvalidasi dan menerapkan kode voucher diskon ke pesanan secara atomik.
     *
     * @throws ValidationException
     */
    public function applyToOrder(Order $order, User $user, string $rawCode): Order
    {
        $code = strtoupper(trim($rawCode));
        if ($code === '') {
            throw ValidationException::withMessages([
                'discount_code' => 'Masukkan kode promo terlebih dahulu.',
            ]);
        }

        return DB::transaction(function () use ($order, $user, $code): Order {
            $lockedOrder = Order::query()->lockForUpdate()->findOrFail($order->id);

            if (! $this->canModifyOrderDiscount($lockedOrder)) {
                throw ValidationException::withMessages([
                    'discount_code' => 'Voucher tidak dapat diterapkan karena pesanan sudah dibayar, selesai, atau sedang dikunci.',
                ]);
            }

            $discount = Discount::query()
                ->where('code', $code)
                ->lockForUpdate()
                ->first();

            if (! $discount) {
                throw ValidationException::withMessages([
                    'discount_code' => "Kode promo \"{$code}\" tidak ditemukan.",
                ]);
            }

            $this->assertDiscountEligible($discount, $lockedOrder, $user);

            $amount = $discount->calculateAmount((int) $lockedOrder->subtotal);
            if ($amount <= 0) {
                throw ValidationException::withMessages([
                    'discount_code' => 'Voucher tidak menghasilkan potongan untuk pesanan ini.',
                ]);
            }

            $previousDiscountId = $lockedOrder->discount_id;
            $isSameDiscount = $previousDiscountId !== null && (int) $previousDiscountId === (int) $discount->id;

            if (! $isSameDiscount) {
                if ($discount->quota !== null && $discount->used_count >= $discount->quota) {
                    throw ValidationException::withMessages([
                        'discount_code' => 'Kuota voucher diskon ini sudah habis.',
                    ]);
                }

                if ($previousDiscountId !== null) {
                    $previousDiscount = Discount::query()->lockForUpdate()->find($previousDiscountId);
                    if ($previousDiscount && $previousDiscount->used_count > 0) {
                        $previousDiscount->decrement('used_count');
                    }
                }

                $discount->increment('used_count');
            }

            $lockedOrder->discount_id = $discount->id;
            $lockedOrder->discount_code = $discount->code;
            $lockedOrder->discount_amount = $amount;
            $lockedOrder->recalculateTotal();
            $lockedOrder->save();

            return $lockedOrder;
        });
    }

    /**
     * Menghapus voucher diskon dari pesanan yang belum dibayar dan mengembalikan kuotanya.
     *
     * @throws ValidationException
     */
    public function removeFromOrder(Order $order): Order
    {
        return DB::transaction(function () use ($order): Order {
            $lockedOrder = Order::query()->lockForUpdate()->findOrFail($order->id);

            if (! $this->canModifyOrderDiscount($lockedOrder)) {
                throw ValidationException::withMessages([
                    'discount_code' => 'Voucher tidak dapat dihapus karena pesanan sudah dibayar, selesai, atau sedang dikunci.',
                ]);
            }

            if ($lockedOrder->discount_id !== null) {
                $previousDiscount = Discount::query()->lockForUpdate()->find($lockedOrder->discount_id);
                if ($previousDiscount && $previousDiscount->used_count > 0) {
                    $previousDiscount->decrement('used_count');
                }
            }

            $lockedOrder->discount_id = null;
            $lockedOrder->discount_code = null;
            $lockedOrder->discount_amount = 0;
            $lockedOrder->recalculateTotal();
            $lockedOrder->save();

            return $lockedOrder;
        });
    }

    /**
     * @throws ValidationException
     */
    private function assertDiscountEligible(Discount $discount, Order $order, User $user): void
    {
        if (! $discount->is_active) {
            throw ValidationException::withMessages([
                'discount_code' => 'Voucher diskon ini sedang tidak aktif.',
            ]);
        }

        $now = now();
        if ($discount->starts_at !== null && $now->lt($discount->starts_at)) {
            throw ValidationException::withMessages([
                'discount_code' => 'Voucher diskon belum berlaku (mulai '.$discount->starts_at->format('d M Y, H:i').' WIB).',
            ]);
        }

        if ($discount->expires_at !== null && $now->gt($discount->expires_at)) {
            throw ValidationException::withMessages([
                'discount_code' => 'Masa berlaku voucher diskon ini sudah berakhir.',
            ]);
        }

        if ($discount->unit !== null && $discount->unit !== $order->unit) {
            throw ValidationException::withMessages([
                'discount_code' => "Voucher ini hanya berlaku untuk unit usaha {$discount->unit->label()}.",
            ]);
        }

        if ((int) $order->subtotal < (int) $discount->min_subtotal) {
            throw ValidationException::withMessages([
                'discount_code' => 'Minimal belanja untuk voucher ini adalah Rp '.number_format($discount->min_subtotal, 0, ',', '.').'.',
            ]);
        }

        if ($discount->max_uses_per_user !== null) {
            $ownerId = $order->user_id ?: $user->id;
            $usedByUser = Order::query()
                ->where('discount_id', $discount->id)
                ->where('user_id', $ownerId)
                ->where('id', '!=', $order->id)
                ->where('status', '!=', OrderStatus::Cancelled)
                ->count();

            if ($usedByUser >= $discount->max_uses_per_user) {
                throw ValidationException::withMessages([
                    'discount_code' => 'Anda sudah mencapai batas maksimal pemakaian voucher ini.',
                ]);
            }
        }
    }
}
