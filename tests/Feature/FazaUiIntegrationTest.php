<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FazaUiIntegrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_renders_the_signed_in_profile_without_transaction_data(): void
    {
        $user = User::factory()->create(['name' => 'Rani', 'full_name' => null]);

        $this->actingAs($user)->get('/dashboard')
            ->assertOk()->assertSee('Rani')->assertSee('Informasi pengguna')
            ->assertDontSee('Proses & Simpan Transaksi')->assertDontSee('Muhammad Irfan Ramadhan');
    }

    public function test_dashboard_profile_form_updates_full_name_and_phone(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->from('/dashboard')->patch('/profile', [
            'full_name' => 'Rani Putri', 'phone' => '081234567890', 'email' => $user->email,
        ])->assertRedirect('/dashboard')->assertSessionHasNoErrors();

        $this->assertDatabaseHas('users', [
            'id' => $user->id, 'name' => 'Rani', 'full_name' => 'Rani Putri', 'phone' => '081234567890',
        ]);
    }

    public function test_avatar_can_be_uploaded(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();

        $this->actingAs($user)->from('/dashboard')->patch('/profile', [
            'email' => $user->email, 'avatar' => UploadedFile::fake()->image('avatar.png'),
        ])->assertRedirect('/dashboard')->assertSessionHasNoErrors();

        $this->assertNotNull($user->refresh()->avatar);
        Storage::disk('public')->assertExists($user->avatar);
    }

    public function test_avatar_delete_only_removes_the_signed_in_users_photo(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('avatars/own.png', 'image');
        Storage::disk('public')->put('avatars/other.png', 'image');
        $user = User::factory()->create(['avatar' => 'avatars/own.png']);
        $other = User::factory()->create(['avatar' => 'avatars/other.png']);

        $this->actingAs($user)->delete('/profile/avatar')->assertRedirect('/dashboard');

        $this->assertNull($user->refresh()->avatar);
        $this->assertSame('avatars/other.png', $other->refresh()->avatar);
        Storage::disk('public')->assertMissing('avatars/own.png');
        Storage::disk('public')->assertExists('avatars/other.png');
    }

    public function test_invalid_avatar_is_rejected_without_changing_profile(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();

        $this->actingAs($user)->from('/dashboard')->patch('/profile', [
            'email' => $user->email, 'avatar' => UploadedFile::fake()->create('file.txt', 1, 'text/plain'),
        ])->assertSessionHasErrors('avatar');

        $this->assertNull($user->refresh()->avatar);
        $this->assertSame([], Storage::disk('public')->allFiles());
    }

    public function test_guest_cannot_delete_avatar_or_access_existing_cart(): void
    {
        $this->delete('/profile/avatar')->assertRedirect('/login');
        $this->get('/kasir/keranjang')->assertRedirect('/login');
    }
}
