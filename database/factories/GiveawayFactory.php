<?php

namespace Database\Factories;

use App\Models\Giveaway;
use Illuminate\Database\Eloquent\Factories\Factory;

class GiveawayFactory extends Factory
{
    protected $model = Giveaway::class;

    public function definition(): array
    {
        return [
            'title' => 'Sorteo de material deportivo',
            'description' => 'Participa para ganar un lote de entrenamiento.',
            'prize' => 'Mochila y botella deportiva',
            'starts_at' => '2026-10-01 00:00:00',
            'ends_at' => '2026-11-01 00:00:00',
            'status' => 'active',
            'winner_user_id' => null,
        ];
    }
}
