<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_password_reset_routes_are_not_available(): void
    {
        $user = User::factory()->create();

        $this->get('/forgot-password')->assertNotFound();
        $this->post('/forgot-password', ['email' => $user->email])->assertNotFound();
        $this->get('/reset-password/token')->assertNotFound();
        $this->post('/reset-password', [
            'token' => 'token',
            'email' => $user->email,
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ])->assertNotFound();

        $user->refresh();

        $this->assertTrue(Hash::check('password', $user->password));
        $this->assertFalse(Hash::check('new-password', $user->password));
    }
}
