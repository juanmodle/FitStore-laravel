<?php

namespace Database\Seeders;

use App\Models\Coupon;
use App\Models\User;
use Illuminate\Database\Seeder;

class CouponUserSeeder extends Seeder
{
    public function run(): void
    {
        $coupon = Coupon::where('code', 'BIENVENIDA10')->firstOrFail();
        $user = User::where('email', 'cliente1@fitstore.test')->firstOrFail();
        if (! $coupon->users()->where('users.id', $user->id)->exists()) {
            $coupon->users()->attach($user->id, ['used_at' => now(), 'times_used' => 1]);
        }
        $coupon->users()->updateExistingPivot($user->id, ['used_at' => now(), 'times_used' => 1]);
    }
}
