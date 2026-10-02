<?php

namespace Tests\Feature;

use App\Models\Menu;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CompleteApplicationFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_cashier_can_complete_the_main_application_flow(): void
    {
        $cashier = User::factory()->create([
            'email' => 'kasir@example.com',
            'password' => bcrypt('password'),
        ]);

        $this->post(route('login'), [
            'email' => $cashier->email,
            'password' => 'password',
        ])->assertRedirect(route('dashboard', absolute: false));

        $this->assertAuthenticatedAs($cashier);

        $this->get(route('menu.index'))
            ->assertOk()
            ->assertSee('Silahkan Pilih Menu yang Anda Inginkan');

        $createResponse = $this->postJson(route('menu.store'), [
            'nama_menu' => 'Kopi Integrasi',
            'kategori' => 'Kopi',
            'harga' => 22000,
            'status_ketersediaan' => 'tersedia',
        ])->assertCreated()
            ->assertJsonPath('message', 'Menu berhasil ditambahkan.');

        $menu = Menu::findOrFail($createResponse->json('menu.id'));

        $this->patchJson(route('menu.update', $menu), [
            'nama_menu' => 'Kopi Integrasi',
            'kategori' => 'Kopi',
            'harga' => 25000,
            'status_ketersediaan' => 'tersedia',
        ])->assertOk()
            ->assertJsonPath('menu.harga', 25000);

        $this->get(route('public.menu'))
            ->assertOk()
            ->assertSee('Kopi Integrasi')
            ->assertSee('Rp 25.000');

        $this->postJson(route('cart.add', $menu), [
            'jumlah' => 2,
        ])->assertOk()
            ->assertJsonPath('item.jumlah', 2)
            ->assertJsonPath('item.subtotal', 50000);

        $checkoutResponse = $this->postJson(route('transactions.store'), [
            'metode_pembayaran' => 'qris',
        ])->assertCreated()
            ->assertJsonPath('message', 'Transaksi berhasil disimpan.')
            ->assertSessionMissing('cart');

        $transaction = Transaksi::findOrFail($checkoutResponse->json('transaksi.id'));

        $this->assertSame(55000, (int) $transaction->total_harga);
        $this->assertDatabaseHas('detail_transaksis', [
            'transaksi_id' => $transaction->id,
            'menu_id' => $menu->id,
            'jumlah' => 2,
            'subtotal' => 50000,
        ]);

        $this->get(route('transactions.index'))
            ->assertOk()
            ->assertSee('Kopi Integrasi')
            ->assertSee('Rp 55.000');

        $this->get(route('transactions.show', $transaction))
            ->assertOk()
            ->assertSee('Kopi Integrasi')
            ->assertSee('QRIS');

        $this->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Rp 55.000')
            ->assertSee('Kopi Integrasi')
            ->assertSee('2 Cup Terjual');
    }
}
