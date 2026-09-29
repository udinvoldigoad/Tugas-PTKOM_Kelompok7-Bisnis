<?php

namespace Tests\Feature;

use App\Models\Menu;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_cart_uses_price_from_database_instead_of_browser_input(): void
    {
        $user = User::factory()->create();
        $menu = $this->createMenu();

        $this->actingAs($user)
            ->postJson(route('cart.add', $menu), [
                'jumlah' => 2,
                'harga' => 1,
                'subtotal' => 2,
            ])
            ->assertOk()
            ->assertJsonPath('item.harga', 22000)
            ->assertJsonPath('item.subtotal', 44000);

        $this->assertEquals(22000, session("cart.{$menu->id}.harga"));
        $this->assertEquals(44000, session("cart.{$menu->id}.subtotal"));
    }

    public function test_unavailable_menu_cannot_be_added_to_cart(): void
    {
        $user = User::factory()->create();
        $menu = $this->createMenu(['status_ketersediaan' => 'habis']);

        $this->actingAs($user)
            ->postJson(route('cart.add', $menu), ['jumlah' => 1])
            ->assertUnprocessable()
            ->assertJsonPath('message', 'Menu ini sedang habis dan tidak dapat ditambahkan.');

        $this->assertNull(session('cart'));
    }

    public function test_cart_rejects_invalid_quantity(): void
    {
        $user = User::factory()->create();
        $menu = $this->createMenu();

        $this->actingAs($user)
            ->postJson(route('cart.add', $menu), ['jumlah' => 0])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('jumlah');

        $this->actingAs($user)
            ->withSession([
                'cart' => [
                    $menu->id => [
                        'id_menu' => $menu->id,
                        'nama_menu' => $menu->nama_menu,
                        'jumlah' => 1,
                        'harga' => $menu->harga,
                        'subtotal' => $menu->harga,
                    ],
                ],
            ])
            ->postJson(route('cart.update', $menu), ['jumlah' => -2])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('jumlah');
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    private function createMenu(array $attributes = []): Menu
    {
        return Menu::create(array_merge([
            'nama_menu' => 'Kopi Susu',
            'kategori' => 'Kopi',
            'harga' => 22000,
            'status_ketersediaan' => 'tersedia',
        ], $attributes));
    }
}
