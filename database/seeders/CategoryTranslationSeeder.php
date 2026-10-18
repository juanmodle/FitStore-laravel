<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\CategoryTranslation;
use Illuminate\Database\Seeder;

class CategoryTranslationSeeder extends Seeder
{
    public function run(): void
    {
        $translations = ['ropa' => 'Sportswear', 'suplementos' => 'Supplements', 'accesorios' => 'Accessories', 'alimentacion' => 'Food'];
        foreach ($translations as $slug => $english) {
            $category = Category::where('slug', $slug)->firstOrFail();
            foreach (['es' => $category->name, 'en' => $english] as $locale => $name) {
                CategoryTranslation::updateOrCreate(['category_id' => $category->id, 'locale' => $locale], ['name' => $name]);
            }
        }
    }
}
