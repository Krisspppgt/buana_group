<?php

namespace App\Models;

use App\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $fillable = [
        'order_id', 'ref', 'method', 'amount', 'status', 'paid_at', 'due_date',
    ];

    protected function casts(): array
    {
        return [
            'status' => PaymentStatus::class,
            'paid_at' => 'datetime',
            'due_date' => 'datetime',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
