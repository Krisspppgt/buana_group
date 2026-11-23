<?php

namespace Database\Factories;

use App\Enums\DiscountType;
use App\Models\Discount;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Discount>
 */
class DiscountFactory extends Factory
{
    protected $model = Discount::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => 'Promo Buana '.fake()->words(2, true),
            'code' => 'BUANA'.strtoupper(Str::random(5)),
            'description' => 'Potongan harga spesial untuk layanan Buana Group.',
            'type' => DiscountType::Percentage,
            'value' => 10,
            'max_discount' => 25000,
            'min_subtotal' => 0,
            'unit' => null,
            'internal_only' => false,
            'quota' => 100,
            'used_count' => 0,
            'max_uses_per_user' => 1,
            'starts_at' => now()->subDay(),
            'expires_at' => now()->addDays(30),
            'is_active' => true,
        ];
    }

    public function fixed(int $amount = 15000): static
    {
        return $this->state(fn (): array => [
            'type' => DiscountType::Fixed,
            'value' => $amount,
            'max_discount' => null,
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn (): array => [
            'is_active' => false,
        ]);
    }

    public function internalOnly(): static
    {
        return $this->state(fn (): array => [
            'internal_only' => true,
        ]);
    }
}
