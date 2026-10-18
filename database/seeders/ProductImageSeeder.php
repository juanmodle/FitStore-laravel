<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Database\Seeder;

class ProductImageSeeder extends Seeder
{
    public function run(): void
    {
        foreach (Product::whereIn('slug', ['camiseta-tecnica', 'pantalon-deportivo', 'proteina-suero', 'creatina', 'botella', 'mancuerna', 'barrita-avena', 'crema-cacahuete'])->get() as $product) {
            ProductImage::updateOrCreate(['product_id' => $product->id, 'position' => 0], [
                'path' => 'products/'.$product->slug.'.jpg', 'alt' => $product->name,
            ]);
        }
    }
}
