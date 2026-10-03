<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use LogicException;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $name = (string) config('deployment.initial_user.name');
        $email = (string) config('deployment.initial_user.email');
        $password = (string) config('deployment.initial_user.password');

        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new LogicException('INITIAL_USER_EMAIL wajib diisi dengan alamat email yang valid sebelum menjalankan seeder.');
        }

        if (mb_strlen($password) < 12) {
            throw new LogicException('INITIAL_USER_PASSWORD wajib diisi minimal 12 karakter sebelum menjalankan seeder.');
        }

        User::updateOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'password' => $password,
            ]
        );
    }
}
