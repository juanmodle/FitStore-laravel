<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderItemFactory extends Factory
{
    protected $model = OrderItem::class;

    public function definition(): array
    {
        return [
            'order_id' => Order::factory(),
            'product_variant_id' => ProductVariant::factory(),
            'product_name' => fn (array $attributes) => ProductVariant::findOrFail($attributes['product_variant_id'])->product->name,
            'sku' => fn (array $attributes) => ProductVariant::findOrFail($attributes['product_variant_id'])->sku,
            'quantity' => 2,
            'unit_price_cents' => 1000,
            'line_total_cents' => 2000,
        ];
    }
}
