<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $permissions = [
            'view users', 'create users', 'edit users', 'delete users',
            'view categories', 'create categories', 'edit categories', 'delete categories',
            'view products', 'create products', 'edit products', 'delete products',
            'view orders', 'create orders', 'edit orders', 'delete orders',
            'update profile', 'change password',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Assign all permissions to admin
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->syncPermissions($permissions);

        // Assign limited permissions to user
        $userRole = Role::firstOrCreate(['name' => 'user']);
        $userRole->syncPermissions([
            'view orders', 'create orders', 'update profile', 'change password',
        ]);
    }
}
