<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesSeeder extends Seeder
{
    public function run(): void
    {
        $roleAdmin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);

        $roleUser = Role::firstOrCreate(['name' => 'user', 'guard_name' => 'web']);

        $permission = Permission::firstOrCreate(['name' => 'manage products', 'guard_name' => 'web']);

        if (!$roleAdmin->hasPermissionTo($permission)) {
            $roleAdmin->givePermissionTo($permission);
        }
        
    }
}
