<?php

namespace Database\Seeders;

use App\Models\Giveaway;
use Illuminate\Database\Seeder;

class GiveawaySeeder extends Seeder
{
    public function run(): void
    {
        Giveaway::updateOrCreate(['title' => 'Sorteo de bienvenida'], [
            'description' => 'Participa para ganar material deportivo.', 'prize' => 'Mochila y botella',
            'starts_at' => '2026-10-01', 'ends_at' => '2026-11-01', 'status' => 'active',
        ]);
        Giveaway::updateOrCreate(['title' => 'Sorteo de septiembre'], [
            'description' => 'Sorteo finalizado.', 'prize' => 'Camiseta deportiva',
            'starts_at' => '2026-09-01', 'ends_at' => '2026-10-01', 'status' => 'closed',
        ]);
    }
}
