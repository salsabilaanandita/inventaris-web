<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'Super Admin',
                'email' => 'superadmin@gmail.com',
                'password' => Hash::make('password123'),
                'role' => 'super_admin'
            ],
            [
                'name' => 'Admin Gudang',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('password123'),
                'role' => 'admin_gudang'
            ],
            [
                'name' => 'Staff Gudang',
                'email' => 'staff@gmail.com',
                'password' => Hash::make('password123'),
                'role' => 'staff_gudang'
            ],
            [
                'name' => 'Manager',
                'email' => 'manager@gmail.com',
                'password' => Hash::make('password123'),
                'role' => 'manager'
            ]
        ];

        foreach ($users as $user) {
            if (!User::where('email', $user['email'])->exists()) {
                User::create($user);
            }
        }
    }
}