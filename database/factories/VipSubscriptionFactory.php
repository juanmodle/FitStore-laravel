<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\VipSubscription;
use Illuminate\Database\Eloquent\Factories\Factory;

class VipSubscriptionFactory extends Factory
{
    protected $model = VipSubscription::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'starts_at' => '2026-10-01 00:00:00',
            'ends_at' => '2026-11-01 00:00:00',
            'price_cents' => 999,
            'currency' => 'EUR',
            'status' => 'pending',
        ];
    }

    public function active(): static
    {
        return $this->state(fn () => ['status' => 'active']);
    }

    public function expired(): static
    {
        return $this->state(fn () => [
            'status' => 'expired',
            'starts_at' => '2025-01-01 00:00:00',
            'ends_at' => '2025-02-01 00:00:00',
        ]);
    }
}
