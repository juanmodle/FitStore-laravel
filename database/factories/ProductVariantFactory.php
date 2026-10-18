<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductVariantFactory extends Factory
{
    protected $model = ProductVariant::class;

    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'sku' => fake()->unique()->bothify('FIT-????-####'),
            'size' => null,
            'color' => null,
            'flavor' => null,
            'format' => 'Estándar',
            'price_cents' => fake()->numberBetween(500, 8000),
            'stock' => fake()->numberBetween(0, 100),
            'is_active' => true,
        ];
    }
}
