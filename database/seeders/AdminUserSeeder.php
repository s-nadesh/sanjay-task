<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        // Ensure admin role exists
        $adminRole = Role::firstOrCreate(['name' => 'admin']);

        // Create admin user (if not exists)
        $admin = User::firstOrCreate(
            ['email' => 'admin@test.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'), // change later
            ]
        );

        // Assign role if not already assigned
        if (! $admin->hasRole('admin')) {
            $admin->assignRole($adminRole);
        }
    
    }
}