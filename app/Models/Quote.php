<?php

namespace App\Models;

use App\Enums\QuoteStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Quote extends Model
{
    // Tabel quotes hanya punya created_at.
    const UPDATED_AT = null;

    protected $fillable = [
        'user_id', 'item', 'qty', 'sizes', 'design', 'est_price',
        'final_price', 'status', 'order_id',
    ];

    protected function casts(): array
    {
        return ['status' => QuoteStatus::class];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
