<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'password' => 'qwerty',
                'roles' => [
                    'Admin'
                ]
            ], [
                'name' => 'Manager',
                'email' => 'manager@example.com',
                'password' => 'qwerty',
                'roles' => [
                    'Manager'
                ]
            ], [
                'name' => 'User',
                'email' => 'user@example.com',
                'password' => 'qwerty'
            ]
        ];

        foreach ($users as $user) {
            User::updateOrCreate([
                'email' => $user['email']
            ], [
                'name' => $user['name'],
                'email_verified_at' => now(),
                'password' => Hash::make($user['password']),
                'remember_token' => Str::random(10)
            ])->syncRoles($user['roles'] ?? []);
        }
    }
}
