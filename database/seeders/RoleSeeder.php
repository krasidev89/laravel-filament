<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'Admin',
                'readonly' => 1
            ], [
                'name' => 'Manager',
                'readonly' => 1,
                'permissions' => [
                    // Products
                    'view-any-product',
                    'view-product',
                    'create-product',
                    'update-product',
                    'delete-product',
                    // Categories
                    'view-any-category',
                    'view-category',
                    'create-category',
                    'update-category',
                    'delete-category',
                    // Users
                    'view-any-user',
                    'view-user',
                    'create-user',
                    'update-user',
                    'delete-user',
                    'restore-user',
                    'force-delete-user',
                    // Roles
                    'view-any-role',
                    'view-role',
                    'create-role',
                    'update-role',
                    'delete-role',
                    // Permissions
                    'view-any-permission',
                    'view-permission',
                    'update-permission',
                ]
            ]
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate([
                'name' => $role['name']
            ], [
                'readonly' => $role['readonly']
            ])->syncPermissions($role['permissions'] ?? []);
        }
    }
}
