<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role; // Use Spatie's Role model

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 🔑 Check if roles exist before creating them to avoid duplicates
        $role1 = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $role2 = Role::firstOrCreate(['name' => 'user', 'guard_name' => 'web']);

        $password = Hash::make('password');

        // Creating Admin User
        $superAdmin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'username' => 'admin',
                'password' => $password,
                'type' => 'admin',
                'status' => 'active',
            ]
        );
        $superAdmin->assignRole($role1);

        // Creating User
        $admin = User::firstOrCreate(
            ['email' => 'editor@example.com'],
            [
                'name' => 'Syed Ahsan Kamal',
                'username' => 'kamal',
                'password' => $password,
                'type' => 'user',
                'status' => 'active',
            ]
        );
        $admin->assignRole($role2);

        // Creating Another User
        $user = User::firstOrCreate(
            ['email' => 'nagham@example.com'],
            [
                'name' => 'Naghman Ali',
                'username' => 'writer',
                'password' => $password,
                'type' => 'user',
                'status' => 'active',
            ]
        );
        $user->assignRole($role2);
    }
}
