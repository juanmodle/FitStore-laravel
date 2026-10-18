<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Payment;
use App\Models\VipSubscription;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition(): array
    {
        return [
            'order_id' => Order::factory(),
            'vip_subscription_id' => null,
            'reference' => fake()->unique()->bothify('SIM-????-########'),
            'amount_cents' => fn (array $attributes) => $attributes['order_id'] ? Order::findOrFail($attributes['order_id'])->total_cents : VipSubscription::findOrFail($attributes['vip_subscription_id'])->price_cents,
            'currency' => 'EUR',
            'status' => 'pending',
            'paid_at' => null,
        ];
    }

    public function paid(): static
    {
        return $this->state(fn () => ['status' => 'paid', 'paid_at' => '2026-10-18 12:05:00']);
    }

    public function forSubscription(VipSubscription $subscription): static
    {
        return $this->state(fn () => [
            'order_id' => null,
            'vip_subscription_id' => $subscription->id,
            'amount_cents' => $subscription->price_cents,
        ]);
    }
}
