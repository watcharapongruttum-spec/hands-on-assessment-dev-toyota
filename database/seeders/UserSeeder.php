<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'mikkee',
            'email' => 'mikkee@test.com',
            'password' => Hash::make('123456'),
        ]);

        User::create([
            'name' => 'Admin Toyota',
            'email' => 'admin@toyota.com',
            'password' => Hash::make('123456'),
        ]);

        User::create([
            'name' => 'Manager',
            'email' => 'manager@toyota.com',
            'password' => Hash::make('123456'),
        ]);
    }
}