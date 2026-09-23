<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Kasir utama sesuai desain referensi (Irfan)
        User::updateOrCreate(
            ['email' => 'muhammad.124140159@student.itera.ac.id'],
            [
                'name' => 'Irfan',
                'full_name' => 'Muhammad Irfan Ramadhan',
                'phone' => '0888 8812 4142',
                'role' => 'Kasir',
                'shift' => '1',
                'outlet_name' => 'Kafe Ridho',
                'outlet_address' => 'Jl.way huwi no 5 lampung',
                'work_hours' => '08:00 - 16:00 (Shift 1)',
                'created_at' => '2022-08-12 08:00:00',
                'last_login_at' => '2026-09-11 09:12:00',
                'password' => Hash::make('password'),
            ]
        );

        // Akun kasir@kasirkafe.test juga tetap disediakan untuk kemudahan login alternatif
        User::updateOrCreate(
            ['email' => 'kasir@kasirkafe.test'],
            [
                'name' => 'Irfan',
                'full_name' => 'Muhammad Irfan Ramadhan',
                'phone' => '0888 8812 4142',
                'role' => 'Kasir',
                'shift' => '1',
                'outlet_name' => 'Kafe Ridho',
                'outlet_address' => 'Jl.way huwi no 5 lampung',
                'work_hours' => '08:00 - 16:00 (Shift 1)',
                'created_at' => '2022-08-12 08:00:00',
                'last_login_at' => '2026-09-11 09:12:00',
                'password' => Hash::make('password'),
            ]
        );
    }
}
