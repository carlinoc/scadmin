<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role1 = Role::create(['name' => 'Admin']);
        $role2 = Role::create(['name' => 'Maitre']);
        $role3 = Role::create(['name' => 'Mozo']);

        $user = User::find(1);
        $user->assignRole('Admin');

        $user1 = User::find(2);
        $user1->assignRole('Mozo');

        // Permission::create(['name' => 'categories.index'])->syncRoles([$role1, $role2]);
        // Permission::create(['name' => 'categories.create'])->syncRoles([$role1, $role2]);
        // Permission::create(['name' => 'categories.edit'])->syncRoles([$role1, $role2]);
        // Permission::create(['name' => 'categories.destroy'])->syncRoles([$role1, $role2]);

    }
}
