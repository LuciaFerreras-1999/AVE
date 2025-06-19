<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::where('name', 'admin')->where('guard_name', 'web')->first();
        if (!$adminRole) {
            $adminRole = Role::create(['name' => 'admin', 'guard_name' => 'web']);
        }
        $userRole = Role::where('name', 'user')->where('guard_name', 'web')->first();
        if (!$userRole) {
            $userRole = Role::create(['name' => 'user', 'guard_name' => 'web']);
        }

        $admin = User::factory()->create([
            'name' => 'Lucia',
            'email' => 'lferreras01f@educantabria.es',
            'password' => bcrypt('usuario@1'),
        ]);
        $admin->assignRole($adminRole);

        $user = User::factory()->create([
            'name' => 'Pepe',
            'email' => 'pepe@educantabria.es',
            'password' => bcrypt('usuario@2'),
        ]);
        $user->assignRole($userRole);

    }
}
