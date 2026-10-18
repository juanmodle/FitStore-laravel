<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductTranslation;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductTranslationFactory extends Factory
{
    protected $model = ProductTranslation::class;

    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'locale' => 'en',
            'name' => 'Sports product',
            'description' => 'Equipment for your daily training.',
        ];
    }
}
