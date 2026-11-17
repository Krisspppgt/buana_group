<?php

namespace App\Models;

use App\Enums\ProductionStage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductionLog extends Model
{
    // Tabel production_logs hanya punya created_at.
    const UPDATED_AT = null;

    protected $fillable = ['order_id', 'stage', 'note'];

    protected function casts(): array
    {
        return ['stage' => ProductionStage::class];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}