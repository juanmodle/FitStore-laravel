<?php

namespace Database\Factories;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'category_id' => Category::factory(),
            'brand_id' => Brand::factory(),
            'name' => fake()->randomElement(['Camiseta técnica', 'Proteína de suero', 'Barrita de avena', 'Mancuerna ajustable']),
            'slug' => fake()->unique()->bothify('producto-????-####'),
            'description' => 'Producto deportivo para acompañar tu entrenamiento diario.',
            'is_active' => true,
            'is_featured' => false,
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn () => ['is_active' => false]);
    }

    public function withVariants(int $count = 1): static
    {
        return $this->has(ProductVariant::factory()->count($count), 'variants');
    }
}
