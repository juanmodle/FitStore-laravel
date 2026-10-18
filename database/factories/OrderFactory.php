<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'coupon_id' => null,
            'number' => fake()->unique()->bothify('FIT-2026-######'),
            'status' => 'pending',
            'currency' => 'EUR',
            'subtotal_cents' => 2000,
            'discount_cents' => 0,
            'shipping_cents' => 300,
            'tax_cents' => 0,
            'total_cents' => 2300,
            'coupon_code' => null,
            'customer_name' => fn (array $attributes) => User::findOrFail($attributes['user_id'])->name,
            'customer_email' => fn (array $attributes) => User::findOrFail($attributes['user_id'])->email,
            'shipping_address' => ['recipient' => 'Cliente de prueba', 'street' => 'Calle Mayor 10', 'city' => 'Madrid', 'province' => 'Madrid', 'postal_code' => '28001', 'country' => 'ES'],
            'billing_address' => fn (array $attributes) => $attributes['shipping_address'],
            'placed_at' => '2026-10-18 12:00:00',
        ];
    }

    public function withItems(): static
    {
        return $this->afterCreating(function (Order $order) {
            OrderItem::factory()->for($order)->create([
                'quantity' => 1,
                'unit_price_cents' => $order->subtotal_cents,
                'line_total_cents' => $order->subtotal_cents,
            ]);
        });
    }

    public function paid(): static
    {
        return $this->state(fn () => ['status' => 'paid']);
    }
}
