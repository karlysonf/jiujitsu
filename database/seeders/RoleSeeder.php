<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roleNames = ['root', 'admin', 'professor', 'instrutor', 'aluno'];

        foreach ($roleNames as $name) {
            Role::firstOrCreate(['name' => $name, 'guard_name' => 'web']);
        }

        $rootRole  = Role::where('name', 'root')->first();
        $adminRole = Role::where('name', 'admin')->first();

        // Assign root role to the main user
        $rootUser = User::where('email', 'kfbasantos@gmail.com')->first();
        if ($rootUser && $rootRole) {
            $rootUser->update(['role_id' => $rootRole->id]);
            $rootUser->syncRoles(['root']);
        }

        // Assign admin role to other users with is_admin = 1
        $admins = User::where('is_admin', 1)
            ->where('email', '!=', 'kfbasantos@gmail.com')
            ->get();

        foreach ($admins as $admin) {
            $admin->update(['role_id' => $adminRole->id]);
            $admin->syncRoles(['admin']);
        }
    }
}
