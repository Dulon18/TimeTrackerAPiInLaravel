<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'demo@example.com'], // check for duplicates
            [
                'name'     => 'Demo User',
                'password' => Hash::make('password123'),
            ]
        );
        $this->command->info('User table seeded successfully!');
    }
}
