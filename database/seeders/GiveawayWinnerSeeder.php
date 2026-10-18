<?php

namespace Database\Seeders;

use App\Models\Giveaway;
use Illuminate\Database\Seeder;

class GiveawayWinnerSeeder extends Seeder
{
    public function run(): void
    {
        $giveaway = Giveaway::where('title', 'Sorteo de septiembre')->firstOrFail();
        $winner = $giveaway->users()->orderBy('users.email')->firstOrFail();

        $giveaway->update(['winner_user_id' => $winner->id]);
    }
}
