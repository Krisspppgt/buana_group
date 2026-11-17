<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TourPackage extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'category', 'departure_date', 'duration', 'price',
        'price_civitas', 'allow_installment', 'min_dp', 'quota', 'booked',
        'itinerary', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'departure_date' => 'date',
            'allow_installment' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(TourBooking::class);
    }

    public function priceFor(?User $user): int
    {
        if ($user?->isCivitas() && $this->price_civitas) {
            return $this->price_civitas;
        }

        return $this->price;
    }
}
