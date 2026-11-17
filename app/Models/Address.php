<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Address extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id', 'label', 'receiver', 'phone', 'detail', 'lat', 'lng', 'is_main',
    ];

    protected function casts(): array
    {
        return ['is_main' => 'boolean'];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}