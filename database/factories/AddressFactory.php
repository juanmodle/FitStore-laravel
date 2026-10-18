<?php

namespace Database\Factories;

use App\Models\Address;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AddressFactory extends Factory
{
    protected $model = Address::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'recipient' => fake()->name(),
            'street' => fake()->streetAddress(),
            'city' => fake()->city(),
            'province' => 'Madrid',
            'postal_code' => fake()->numerify('28###'),
            'country' => 'ES',
        ];
    }
}
