<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\PostTranslation;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostTranslationFactory extends Factory
{
    protected $model = PostTranslation::class;

    public function definition(): array
    {
        return [
            'post_id' => Post::factory(),
            'locale' => 'en',
            'title' => 'Getting started with training',
            'content' => 'Start slowly and remember to rest.',
        ];
    }
}
