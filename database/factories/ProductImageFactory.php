<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductImageFactory extends Factory
{
    protected $model = ProductImage::class;

    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'path' => 'products/'.fake()->unique()->bothify('imagen-????-####').'.jpg',
            'alt' => 'Imagen del producto deportivo',
            'position' => 0,
        ];
    }
}
