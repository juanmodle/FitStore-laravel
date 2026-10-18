<?php

namespace Database\Seeders;

use App\Models\Giveaway;
use App\Models\User;
use Illuminate\Database\Seeder;

class GiveawayEntrySeeder extends Seeder
{
    public function run(): void
    {
        $users = User::where('email', 'like', 'cliente%@fitstore.test')->orderBy('email')->get();
        $giveaway = Giveaway::where('title', 'Sorteo de bienvenida')->firstOrFail();
        $closed = Giveaway::where('title', 'Sorteo de septiembre')->firstOrFail();
        foreach ($users as $user) {
            $giveaway->users()->syncWithoutDetaching([$user->id => ['joined_at' => '2026-10-10 12:00:00']]);
            $closed->users()->syncWithoutDetaching([$user->id => ['joined_at' => '2026-09-10 12:00:00']]);
        }
    }
}
