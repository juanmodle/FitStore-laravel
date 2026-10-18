<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\PostCategory;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'post_category_id' => PostCategory::factory(),
            'title' => 'Consejos para empezar a entrenar',
            'slug' => fake()->unique()->bothify('publicacion-????-####'),
            'content' => 'Empieza de forma progresiva, descansa y mantén una alimentación equilibrada.',
            'status' => 'draft',
            'published_at' => null,
        ];
    }

    public function published(): static
    {
        return $this->state(fn () => ['status' => 'published', 'published_at' => '2026-10-01 12:00:00']);
    }
}
