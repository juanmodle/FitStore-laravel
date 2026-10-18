<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Seeder;

class ProductVariantSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            ['camiseta-tecnica', 1999, 'size', ['M', 'L']],
            ['pantalon-deportivo', 2499, 'size', ['S', 'M']],
            ['proteina-suero', 2990, 'flavor', ['Chocolate', 'Vainilla']],
            ['creatina', 1890, 'format', ['300 g', '500 g']],
            ['botella', 990, 'color', ['Azul', 'Negro']],
            ['mancuerna', 1590, 'format', ['5 kg', '10 kg']],
            ['barrita-avena', 250, 'flavor', ['Cacao', 'Coco']],
            ['crema-cacahuete', 650, 'format', ['250 g', '500 g']],
        ];

        foreach ($products as $index => [$slug, $price, $option, $values]) {
            $product = Product::where('slug', $slug)->firstOrFail();
            foreach ($values as $position => $value) {
                ProductVariant::updateOrCreate(['sku' => 'FIT-'.($index + 1).'-'.($position + 1)], [
                    'product_id' => $product->id, 'size' => null, 'color' => null, 'flavor' => null,
                    'format' => null, $option => $value, 'price_cents' => $price + $position * 100,
                    'stock' => 10 + $index + $position, 'is_active' => true,
                ]);
            }
        }
    }
}
