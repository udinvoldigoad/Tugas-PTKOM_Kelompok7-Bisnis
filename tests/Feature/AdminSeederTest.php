<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\AdminSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use LogicException;
use Tests\TestCase;

class AdminSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_initial_user_is_created_from_deployment_configuration(): void
    {
        config()->set('deployment.initial_user', [
            'name' => 'Kasir Produksi',
            'email' => 'kasir@example.com',
            'password' => 'rahasia-kuat-123',
        ]);

        $this->seed(AdminSeeder::class);

        $user = User::where('email', 'kasir@example.com')->firstOrFail();

        $this->assertSame('Kasir Produksi', $user->name);
        $this->assertTrue(Hash::check('rahasia-kuat-123', $user->password));
    }

    public function test_initial_user_password_is_required_and_must_be_secure(): void
    {
        config()->set('deployment.initial_user', [
            'name' => 'Kasir Produksi',
            'email' => 'kasir@example.com',
            'password' => 'password',
        ]);

        $this->expectException(LogicException::class);
        $this->seed(AdminSeeder::class);
    }
}
