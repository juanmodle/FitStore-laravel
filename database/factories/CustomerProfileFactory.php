<?php

namespace Database\Factories;

use App\Models\CustomerProfile;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerProfileFactory extends Factory
{
    protected $model = CustomerProfile::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'phone' => fake()->numerify('6########'),
            'points' => fake()->numberBetween(0, 500),
        ];
    }
}
