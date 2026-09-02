<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'kasir@kasirkafe.test'],
            [
                'name' => 'Kasir Kafe',
                'password' => Hash::make('password'),
            ]
        );
    }
}