<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['fitstore' => 'FitStore', 'sportline' => 'SportLine', 'nutrifit' => 'NutriFit'] as $slug => $name) {
            Brand::updateOrCreate(['slug' => $slug], ['name' => $name]);
        }
    }
}
