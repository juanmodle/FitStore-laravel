<?php

namespace Database\Factories;

use App\Models\Coupon;
use App\Models\Discount;
use Illuminate\Database\Eloquent\Factories\Factory;

class CouponFactory extends Factory
{
    protected $model = Coupon::class;

    public function definition(): array
    {
        return [
            'discount_id' => Discount::factory(),
            'code' => fake()->unique()->bothify('FIT-????-####'),
            'starts_at' => '2026-01-01 00:00:00',
            'ends_at' => '2027-01-01 00:00:00',
            'minimum_order_cents' => 1000,
            'max_uses' => 100,
            'max_uses_per_user' => 2,
            'is_active' => true,
        ];
    }

    public function expired(): static
    {
        return $this->state(fn () => ['starts_at' => '2024-01-01 00:00:00', 'ends_at' => '2025-01-01 00:00:00']);
    }
}
