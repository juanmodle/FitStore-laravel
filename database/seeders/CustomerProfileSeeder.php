<?php

namespace Database\Seeders;

use App\Models\CustomerProfile;
use App\Models\User;
use Illuminate\Database\Seeder;

class CustomerProfileSeeder extends Seeder
{
    public function run(): void
    {
        foreach (range(1, 4) as $number) {
            $user = User::where('email', 'cliente'.$number.'@fitstore.test')->firstOrFail();
            CustomerProfile::updateOrCreate(['user_id' => $user->id], [
                'phone' => '60000000'.$number, 'points' => ($number - 1) * 50,
            ]);
        }
    }
}
