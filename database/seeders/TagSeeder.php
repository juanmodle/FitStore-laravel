<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['entrenamiento' => 'Entrenamiento', 'novedad' => 'Novedad', 'fitness' => 'Fitness'] as $slug => $name) {
            Tag::updateOrCreate(['slug' => $slug], ['name' => $name]);
        }
    }
}
