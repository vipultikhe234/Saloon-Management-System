<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Super Admin
        User::create([
            'name' => 'Super Admin',
            'email' => 'admin@saloon.com',
            'password' => Hash::make('password'),
            'role' => 'super_admin',
            'is_active' => true,
            'phone' => '1234567890',
        ]);

        // Create Demo Saloon Admin
        User::create([
            'name' => 'Saloon Owner',
            'email' => 'saloon@saloon.com',
            'password' => Hash::make('password'),
            'role' => 'saloon_admin',
            'is_active' => true,
            'phone' => '9876543210',
        ]);

        // Create Demo User
        User::create([
            'name' => 'John Doe',
            'email' => 'user@saloon.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'is_active' => true,
            'phone' => '5555555555',
        ]);

        $this->call([
            CategorySeeder::class,
            SaloonSeeder::class,
        ]);
    }
}
