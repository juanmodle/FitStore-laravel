<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserRoleSeeder extends Seeder
{
    public function run(): void
    {
        foreach (range(1, 4) as $number) {
            $user = User::where('email', 'cliente'.$number.'@fitstore.test')->firstOrFail();
            $user->syncRoles($number === 1 ? ['customer', 'vip_customer'] : ['customer']);
        }

        foreach (['editor', 'manager', 'admin'] as $role) {
            User::where('email', $role.'@fitstore.test')->firstOrFail()->syncRoles([$role]);
        }
    }
}
