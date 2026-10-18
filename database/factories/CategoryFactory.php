<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
    {
        return [
            'name' => fake()->randomElement(['Ropa deportiva', 'Suplementos', 'Accesorios', 'Alimentación']),
            'slug' => fake()->unique()->bothify('categoria-????-####'),
            'is_active' => true,
        ];
    }
}
