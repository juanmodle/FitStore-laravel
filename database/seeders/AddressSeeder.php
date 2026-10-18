<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\User;
use Illuminate\Database\Seeder;

class AddressSeeder extends Seeder
{
    public function run(): void
    {
        foreach (range(1, 4) as $number) {
            $user = User::where('email', 'cliente'.$number.'@fitstore.test')->firstOrFail();
            Address::updateOrCreate(['user_id' => $user->id, 'street' => 'Calle Mayor '.$number], [
                'recipient' => $user->name, 'city' => 'Madrid', 'province' => 'Madrid',
                'postal_code' => '28001', 'country' => 'ES',
            ]);
        }
    }
}
