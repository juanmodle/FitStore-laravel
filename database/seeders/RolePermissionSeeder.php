<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'customer' => ['account.access', 'orders.view-own'],
            'vip_customer' => ['account.access', 'orders.view-own', 'vip.access'],
            'editor' => ['account.access', 'catalog.view-drafts'],
            'manager' => ['account.access', 'orders.view-own', 'catalog.view-drafts', 'orders.view-all'],
            'admin' => ['account.access', 'orders.view-own', 'catalog.view-drafts', 'orders.view-all', 'users.view', 'vip.access'],
        ];

        foreach ($permissions as $role => $names) {
            Role::findByName($role, 'web')->syncPermissions($names);
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
