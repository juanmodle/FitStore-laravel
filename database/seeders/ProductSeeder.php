<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            ['camiseta-tecnica', 'Camiseta técnica', 'ropa', 'sportline'],
            ['pantalon-deportivo', 'Pantalón deportivo', 'ropa', 'sportline'],
            ['proteina-suero', 'Proteína de suero', 'suplementos', 'nutrifit'],
            ['creatina', 'Creatina monohidrato', 'suplementos', 'nutrifit'],
            ['botella', 'Botella deportiva', 'accesorios', 'fitstore'],
            ['mancuerna', 'Mancuerna', 'accesorios', 'fitstore'],
            ['barrita-avena', 'Barrita de avena', 'alimentacion', 'nutrifit'],
            ['crema-cacahuete', 'Crema de cacahuete', 'alimentacion', 'nutrifit'],
        ];

        foreach ($products as $index => [$slug, $name, $category, $brand]) {
            Product::updateOrCreate(['slug' => $slug], [
                'name' => $name, 'description' => 'Producto para acompañar tu entrenamiento diario.',
                'category_id' => Category::where('slug', $category)->firstOrFail()->id, 'brand_id' => Brand::where('slug', $brand)->firstOrFail()->id,
                'is_active' => true, 'is_featured' => $index < 2,
            ]);
        }
    }
}
