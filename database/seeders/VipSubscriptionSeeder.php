<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\VipSubscription;
use Illuminate\Database\Seeder;

class VipSubscriptionSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('email', 'cliente1@fitstore.test')->firstOrFail();
        VipSubscription::updateOrCreate(['user_id' => $user->id, 'starts_at' => '2026-10-01 00:00:00'], [
            'ends_at' => '2026-11-01 00:00:00', 'price_cents' => 999, 'currency' => 'EUR', 'status' => 'active',
        ]);
    }
}
