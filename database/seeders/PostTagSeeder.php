<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\Tag;
use Illuminate\Database\Seeder;

class PostTagSeeder extends Seeder
{
    public function run(): void
    {
        $tag = Tag::where('slug', 'entrenamiento')->firstOrFail();
        foreach (Post::whereIn('slug', ['primer-entrenamiento', 'descanso'])->get() as $post) {
            $post->tags()->syncWithoutDetaching([$tag->id]);
        }
    }
}
