<?php

namespace Tests\Feature;

use App\Models\Menu;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_from_dashboard(): void
    {
        $response = $this->get('/dashboard');

        $response->assertRedirect('/login');
    }

    public function test_authenticated_user_can_view_redesigned_dashboard(): void
    {
        $user = User::factory()->create([
            'name' => 'Irfan',
            'full_name' => 'Muhammad Irfan Ramadhan',
            'role' => 'Kasir',
            'shift' => '1',
            'outlet_name' => 'Kafe Ridho',
            'outlet_address' => 'Jl.way huwi no 5 lampung',
            'work_hours' => '08:00 - 16:00 (Shift 1)',
        ]);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertOk();
        $response->assertSee('Profil');
        $response->assertSee('Kelola Informasi Akun Anda');
        $response->assertSee('Irfan');
        $response->assertSee('Muhammad Irfan Ramadhan');
        $response->assertSee('Kafe Ridho');
        $response->assertSee('Jl.way huwi no 5 lampung');
        $response->assertSee('Informasi pengguna');
        $response->assertSee('Informasi Tambahan');
    }

    public function test_authenticated_user_can_create_transaksi(): void
    {
        $user = User::factory()->create();
        $menu = Menu::create([
            'nama_menu' => 'Espresso',
            'kategori' => 'Kopi',
            'harga' => 18000,
            'status_ketersediaan' => 'tersedia',
        ]);

        $response = $this->actingAs($user)->post('/transaksi', [
            'items' => [
                ['id' => $menu->id, 'qty' => 2],
            ],
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('transaksis', [
            'user_id' => $user->id,
            'total_harga' => 36000,
        ]);
        $this->assertDatabaseHas('detail_transaksis', [
            'menu_id' => $menu->id,
            'jumlah' => 2,
            'subtotal' => 36000,
        ]);
    }
}
