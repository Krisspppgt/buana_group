<?php

namespace App\Models;

use App\Enums\Unit;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'unit', 'name', 'info', 'price', 'price_civitas', 'price_bulk',
        'bulk_min', 'stock', 'uom', 'photo', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'unit' => Unit::class,
            'is_active' => 'boolean',
        ];
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Harga satuan untuk pengguna dan jumlah tertentu.
     * Diambil yang paling murah di antara harga yang berlaku.
     */
    public function priceFor(?User $user, int $qty = 1): int
    {
        $prices = [$this->price];

        if ($user?->isCivitas() && $this->price_civitas) {
            $prices[] = $this->price_civitas;
        }

        if ($this->price_bulk && $this->bulk_min && $qty >= $this->bulk_min) {
            $prices[] = $this->price_bulk;
        }

        return min($prices);
    }
}