<?php

namespace App\Services;

use App\Enums\StockMovementType;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Support\Facades\Auth;

class StockService
{
    /**
     * Mencatat stok masuk dan menambah saldo stok produk jika diminta.
     */
    public function stockIn(
        Product $product,
        int $qty,
        string $reason = 'restock',
        ?int $userId = null,
        ?int $orderId = null,
        ?string $note = null,
        bool $mutateProduct = true,
    ): StockMovement {
        $stockBefore = (int) $product->stock;

        if ($mutateProduct && $qty > 0) {
            $product->increment('stock', $qty);
            $product->refresh();
        }

        $stockAfter = $mutateProduct ? (int) $product->stock : $stockBefore + $qty;

        return StockMovement::create([
            'product_id' => $product->id,
            'user_id' => $userId ?? Auth::id(),
            'order_id' => $orderId,
            'type' => StockMovementType::In,
            'reason' => $reason,
            'qty' => $qty,
            'stock_before' => $stockBefore,
            'stock_after' => $stockAfter,
            'note' => $note,
            'created_at' => now(),
        ]);
    }

    /**
     * Mencatat stok keluar dan mengurangi saldo stok produk jika diminta.
     */
    public function stockOut(
        Product $product,
        int $qty,
        string $reason = 'order',
        ?int $userId = null,
        ?int $orderId = null,
        ?string $note = null,
        bool $mutateProduct = true,
    ): StockMovement {
        $stockBefore = (int) $product->stock;

        if ($mutateProduct && $qty > 0) {
            $product->decrement('stock', $qty);
            $product->refresh();
        }

        $stockAfter = $mutateProduct ? (int) $product->stock : max(0, $stockBefore - $qty);

        return StockMovement::create([
            'product_id' => $product->id,
            'user_id' => $userId ?? Auth::id(),
            'order_id' => $orderId,
            'type' => StockMovementType::Out,
            'reason' => $reason,
            'qty' => $qty,
            'stock_before' => $stockBefore,
            'stock_after' => $stockAfter,
            'note' => $note,
            'created_at' => now(),
        ]);
    }

    /**
     * Mencatat penyesuaian stok (opname / edit manual) ke nilai stok terbaru.
     */
    public function adjust(
        Product $product,
        int $newStock,
        string $reason = 'opname',
        ?int $userId = null,
        ?string $note = null,
    ): ?StockMovement {
        $stockBefore = (int) $product->stock;
        $diff = $newStock - $stockBefore;

        if ($diff === 0) {
            return null;
        }

        $product->update(['stock' => $newStock]);

        return StockMovement::create([
            'product_id' => $product->id,
            'user_id' => $userId ?? Auth::id(),
            'type' => StockMovementType::Adjustment,
            'reason' => $reason,
            'qty' => abs($diff),
            'stock_before' => $stockBefore,
            'stock_after' => $newStock,
            'note' => $note,
            'created_at' => now(),
        ]);
    }
}
