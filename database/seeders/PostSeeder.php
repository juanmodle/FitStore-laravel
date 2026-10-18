<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\PostCategory;
use App\Models\User;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('email', 'cliente1@fitstore.test')->firstOrFail();
        $category = PostCategory::where('slug', 'entrenamiento')->firstOrFail();
        foreach (['primer-entrenamiento' => 'Tu primer entrenamiento', 'descanso' => 'La importancia del descanso'] as $slug => $title) {
            Post::updateOrCreate(['slug' => $slug], [
                'user_id' => $user->id, 'post_category_id' => $category->id,
                'title' => $title, 'content' => 'Entrena de forma progresiva y reserva tiempo para descansar.',
                'status' => 'published', 'published_at' => '2026-10-01 12:00:00',
            ]);
        }
    }
}
