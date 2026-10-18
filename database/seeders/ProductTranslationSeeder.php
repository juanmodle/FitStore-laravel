<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductTranslation;
use Illuminate\Database\Seeder;

class ProductTranslationSeeder extends Seeder
{
    public function run(): void
    {
        $translations = [
            'camiseta-tecnica' => 'Training shirt', 'pantalon-deportivo' => 'Training shorts',
            'proteina-suero' => 'Whey protein', 'creatina' => 'Creatine', 'botella' => 'Sports bottle',
            'mancuerna' => 'Dumbbell', 'barrita-avena' => 'Oat bar', 'crema-cacahuete' => 'Peanut butter',
        ];
        foreach ($translations as $slug => $english) {
            $product = Product::where('slug', $slug)->firstOrFail();
            foreach (['es' => $product->name, 'en' => $english] as $locale => $name) {
                ProductTranslation::updateOrCreate(['product_id' => $product->id, 'locale' => $locale], [
                    'name' => $name,
                    'description' => $locale === 'es' ? $product->description : 'Equipment for your daily training.',
                ]);
            }
        }
    }
}
