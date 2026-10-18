<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        foreach (['ropa' => 'Ropa deportiva', 'suplementos' => 'Suplementos', 'accesorios' => 'Accesorios', 'alimentacion' => 'Alimentación'] as $slug => $name) {
            Category::updateOrCreate(['slug' => $slug], ['name' => $name, 'is_active' => true]);
        }
    }
}
