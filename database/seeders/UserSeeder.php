<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $names = ['Ana García', 'Luis Martín', 'Marta López', 'Pablo Ruiz'];

        foreach ($names as $index => $name) {
            $user = User::firstOrCreate(['email' => 'cliente'.($index + 1).'@fitstore.test'], [
                'name' => $name, 'password' => Hash::make('password'),
                'locale' => $index === 3 ? 'en' : 'es', 'email_verified_at' => now(),
            ]);
        }

        foreach (['editor' => 'Editor FitStore', 'manager' => 'Manager FitStore', 'admin' => 'Administrador FitStore'] as $role => $name) {
            User::firstOrCreate(['email' => $role.'@fitstore.test'], [
                'name' => $name, 'password' => Hash::make('password'),
                'locale' => 'es', 'email_verified_at' => now(),
            ]);
        }
    }
}
