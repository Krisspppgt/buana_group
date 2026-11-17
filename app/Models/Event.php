<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'organizer', 'event_date', 'location',
        'price', 'price_civitas', 'quota', 'booked', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'event_date' => 'datetime',
            'is_active' => 'boolean',
        ];
    }

    public function priceFor(?User $user): int
    {
        if ($user?->isCivitas() && $this->price_civitas !== null) {
            return $this->price_civitas;
        }

        return $this->price;
    }
}
