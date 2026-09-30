<?php

namespace Tests\Feature;

use App\Models\DetailTransaksi;
use App\Models\Menu;
use App\Models\Transaksi;
use App\Models\User;
use Carbon\CarbonInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransactionHistoryRouteTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_transaction_history_routes(): void
    {
        $cashier = User::factory()->create();
        $transaction = $this->createTransaction($cashier);

        $this->get(route('transactions.index'))->assertRedirect(route('login'));
        $this->get(route('transactions.show', $transaction))->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_view_latest_transaction_list(): void
    {
        $cashier = User::factory()->create(['name' => 'Qinta']);
        $olderTransaction = $this->createTransaction($cashier, now()->subDay());
        $latestTransaction = $this->createTransaction($cashier, now());

        $response = $this->actingAs($cashier)->get(route('transactions.index'));

        $response->assertOk()
            ->assertViewIs('riwayat.index')
            ->assertViewHas('transaksis', function ($transaksis) use ($latestTransaction, $olderTransaction): bool {
                return $transaksis->getCollection()->modelKeys() === [
                    $latestTransaction->id,
                    $olderTransaction->id,
                ];
            });
    }

    public function test_authenticated_user_sees_empty_state_when_there_are_no_transactions(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('transactions.index'));

        $response->assertOk()
            ->assertViewIs('riwayat.index')
            ->assertViewHas('transaksis', fn ($transaksis): bool => $transaksis->isEmpty())
            ->assertSee('Belum ada transaksi.');
    }

    public function test_authenticated_user_can_view_transaction_details_with_deleted_menu(): void
    {
        $cashier = User::factory()->create(['name' => 'Qinta']);
        $menu = Menu::create([
            'nama_menu' => 'Kopi Susu',
            'kategori' => 'Kopi',
            'harga' => 22000,
            'status_ketersediaan' => 'tersedia',
        ]);
        $transaction = $this->createTransaction($cashier);
        DetailTransaksi::create([
            'transaksi_id' => $transaction->id,
            'menu_id' => $menu->id,
            'jumlah' => 1,
            'subtotal' => 22000,
        ]);
        $menu->delete();

        $response = $this->actingAs($cashier)->get(route('transactions.show', $transaction));

        $response->assertOk()
            ->assertViewIs('riwayat.show')
            ->assertViewHas('transaksi', function (Transaksi $viewTransaction): bool {
                return $viewTransaction->relationLoaded('user')
                    && $viewTransaction->relationLoaded('detailTransaksi')
                    && $viewTransaction->detailTransaksi->first()->relationLoaded('menu')
                    && $viewTransaction->detailTransaksi->first()->menu->trashed();
            })
            ->assertSee('Kopi Susu');
    }

    public function test_missing_transaction_detail_returns_not_found(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('transactions.show', 999999))
            ->assertNotFound();
    }

    private function createTransaction(User $cashier, ?CarbonInterface $date = null): Transaksi
    {
        return Transaksi::create([
            'user_id' => $cashier->id,
            'tanggal' => $date ?? now(),
            'total_harga' => 24200,
            'metode_pembayaran' => 'cash',
        ]);
    }
}
