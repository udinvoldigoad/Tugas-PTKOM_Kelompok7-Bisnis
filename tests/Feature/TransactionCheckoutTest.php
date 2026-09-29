<?php

namespace Tests\Feature;

use App\Models\DetailTransaksi;
use App\Models\Menu;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use RuntimeException;
use Tests\TestCase;

class TransactionCheckoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_cashier_can_store_cart_as_one_transaction(): void
    {
        $user = User::factory()->create();
        $menu = $this->createMenu();
        $cart = [
            $menu->id => [
                'id_menu' => $menu->id,
                'nama_menu' => $menu->nama_menu,
                'jumlah' => 2,
                'harga' => 1,
                'subtotal' => 2,
            ],
        ];

        $this->actingAs($user)
            ->withSession(['cart' => $cart])
            ->postJson(route('transactions.store'))
            ->assertCreated()
            ->assertJsonPath('message', 'Transaksi berhasil disimpan.')
            ->assertSessionMissing('cart');

        $this->assertDatabaseHas('transaksis', [
            'user_id' => $user->id,
            'total_harga' => 48400,
        ]);
        $this->assertDatabaseHas('detail_transaksis', [
            'menu_id' => $menu->id,
            'jumlah' => 2,
            'subtotal' => 44000,
        ]);
    }

    public function test_transaction_is_not_partially_stored_when_a_menu_is_unavailable(): void
    {
        $user = User::factory()->create();
        $availableMenu = $this->createMenu();
        $unavailableMenu = $this->createMenu([
            'nama_menu' => 'Lemon Tea',
            'status_ketersediaan' => 'habis',
        ]);
        $cart = [
            $availableMenu->id => ['jumlah' => 1],
            $unavailableMenu->id => ['jumlah' => 1],
        ];

        $this->actingAs($user)
            ->withSession(['cart' => $cart])
            ->postJson(route('transactions.store'))
            ->assertUnprocessable()
            ->assertJsonValidationErrors('cart');

        $this->assertDatabaseCount('transaksis', 0);
        $this->assertDatabaseCount('detail_transaksis', 0);
        $this->assertEquals($cart, session('cart'));
    }

    public function test_empty_or_already_processed_cart_cannot_create_another_transaction(): void
    {
        $user = User::factory()->create();
        $menu = $this->createMenu();
        $cart = [
            $menu->id => [
                'id_menu' => $menu->id,
                'nama_menu' => $menu->nama_menu,
                'jumlah' => 1,
                'harga' => $menu->harga,
                'subtotal' => $menu->harga,
            ],
        ];

        $this->actingAs($user)
            ->withSession(['cart' => $cart])
            ->postJson(route('transactions.store'))
            ->assertCreated();

        $this->postJson(route('transactions.store'))
            ->assertUnprocessable()
            ->assertJsonValidationErrors('cart');

        $this->assertDatabaseCount('transaksis', 1);
        $this->assertDatabaseCount('detail_transaksis', 1);
    }

    public function test_database_failure_rolls_back_transaction_and_keeps_cart(): void
    {
        $user = User::factory()->create();
        $menu = $this->createMenu();
        $cart = [
            $menu->id => [
                'id_menu' => $menu->id,
                'nama_menu' => $menu->nama_menu,
                'jumlah' => 1,
                'harga' => $menu->harga,
                'subtotal' => $menu->harga,
            ],
        ];
        DetailTransaksi::creating(function (): void {
            throw new RuntimeException('Simulasi kegagalan database.');
        });

        try {
            $this->actingAs($user)
                ->withSession(['cart' => $cart])
                ->postJson(route('transactions.store'))
                ->assertServerError();
        } finally {
            DetailTransaksi::flushEventListeners();
        }

        $this->assertDatabaseCount('transaksis', 0);
        $this->assertDatabaseCount('detail_transaksis', 0);
        $this->assertEquals($cart, session('cart'));
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
