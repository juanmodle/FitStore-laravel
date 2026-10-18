<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\PostTranslation;
use Illuminate\Database\Seeder;

class PostTranslationSeeder extends Seeder
{
    public function run(): void
    {
        foreach (Post::whereIn('slug', ['primer-entrenamiento', 'descanso'])->get() as $post) {
            foreach (['es', 'en'] as $locale) {
                PostTranslation::updateOrCreate(['post_id' => $post->id, 'locale' => $locale], [
                    'title' => $locale === 'es' ? $post->title : ($post->slug === 'descanso' ? 'The importance of rest' : 'Your first workout'),
                    'content' => $locale === 'es' ? $post->content : 'Train progressively and take time to rest.',
                ]);
            }
        }
    }
}
