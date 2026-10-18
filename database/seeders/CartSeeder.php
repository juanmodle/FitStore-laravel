<?php

namespace Database\Seeders;

use App\Models\Cart;
use App\Models\User;
use Illuminate\Database\Seeder;

class CartSeeder extends Seeder
{
    public function run(): void
    {
        foreach (range(1, 4) as $number) {
            $user = User::where('email', 'cliente'.$number.'@fitstore.test')->firstOrFail();
            Cart::updateOrCreate(['user_id' => $user->id], ['last_activity_at' => now()]);
        }
    }
}
