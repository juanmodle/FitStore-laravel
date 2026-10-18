<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Tag;
use Illuminate\Database\Seeder;

class ProductTagSeeder extends Seeder
{
    public function run(): void
    {
        $training = Tag::where('slug', 'entrenamiento')->firstOrFail();
        $fitness = Tag::where('slug', 'fitness')->firstOrFail();
        foreach (Product::whereIn('slug', ['camiseta-tecnica', 'pantalon-deportivo', 'proteina-suero', 'creatina', 'botella', 'mancuerna', 'barrita-avena', 'crema-cacahuete'])->get() as $product) {
            $product->tags()->syncWithoutDetaching([
                $training->id => ['priority' => 1, 'is_visible' => true],
                $fitness->id => ['priority' => 2, 'is_visible' => false],
            ]);
            $product->tags()->updateExistingPivot($fitness->id, ['priority' => 3, 'is_visible' => true]);
        }
    }
}
