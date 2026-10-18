<?php

namespace Database\Factories;

use App\Models\Discount;
use Illuminate\Database\Eloquent\Factories\Factory;

class DiscountFactory extends Factory
{
    protected $model = Discount::class;

    public function definition(): array
    {
        return [
            'name' => 'Descuento de bienvenida',
            'type' => 'percentage',
            'value' => 10,
            'starts_at' => '2026-01-01 00:00:00',
            'ends_at' => '2027-01-01 00:00:00',
            'is_active' => true,
            'vip_only' => false,
        ];
    }

    public function expired(): static
    {
        return $this->state(fn () => ['starts_at' => '2024-01-01 00:00:00', 'ends_at' => '2025-01-01 00:00:00']);
    }

    public function fixed(int $cents = 500): static
    {
        return $this->state(fn () => ['type' => 'fixed', 'value' => $cents]);
    }
}
