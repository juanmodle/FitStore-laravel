<?php

namespace Database\Factories;

use App\Models\PostCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostCategoryFactory extends Factory
{
    protected $model = PostCategory::class;

    public function definition(): array
    {
        return [
            'name' => fake()->unique()->words(2, true),
            'slug' => fake()->unique()->bothify('blog-????-####'),
        ];
    }
}
