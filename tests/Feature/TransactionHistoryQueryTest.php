<?php

namespace Tests\Feature;

use App\Models\DetailTransaksi;
use App\Models\Menu;
use App\Models\Transaksi;
use App\Models\User;
use Carbon\CarbonInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransactionHistoryQueryTest extends TestCase
{
    use RefreshDatabase;

    public function test_latest_history_is_ordered_and_eager_loads_deleted_menu_details(): void
    {
        $cashier = User::factory()->create(['name' => 'Qinta']);
        $menu = Menu::create([
            'nama_menu' => 'Kopi Susu',
            'kategori' => 'Kopi',
            'harga' => 22000,
            'status_ketersediaan' => 'tersedia',
        ]);
        $olderTransaction = $this->createTransaction($cashier, now()->subDay());
        $latestTime = now();
        $firstLatestTransaction = $this->createTransaction($cashier, $latestTime);
        $secondLatestTransaction = $this->createTransaction($cashier, $latestTime);

        DetailTransaksi::create([
            'transaksi_id' => $secondLatestTransaction->id,
            'menu_id' => $menu->id,
            'jumlah' => 1,
            'subtotal' => 22000,
        ]);
        $menu->delete();

        $history = Transaksi::query()->riwayatTerbaru()->get();

        $this->assertSame([
            $secondLatestTransaction->id,
            $firstLatestTransaction->id,
            $olderTransaction->id,
        ], $history->modelKeys());

        $latestTransaction = $history->first();
        $this->assertTrue($latestTransaction->relationLoaded('user'));
        $this->assertTrue($latestTransaction->relationLoaded('detailTransaksi'));
        $this->assertSame('Qinta', $latestTransaction->user->name);
        $this->assertTrue($latestTransaction->detailTransaksi->first()->relationLoaded('menu'));
        $this->assertSame('Kopi Susu', $latestTransaction->detailTransaksi->first()->menu->nama_menu);
        $this->assertNotNull($latestTransaction->detailTransaksi->first()->menu->deleted_at);
    }

    public function test_historical_amounts_remain_stable_after_menu_is_changed_and_deleted(): void
    {
        $cashier = User::factory()->create();
        $menu = Menu::create([
            'nama_menu' => 'Kopi Susu',
            'kategori' => 'Kopi',
            'harga' => 22000,
            'status_ketersediaan' => 'tersedia',
        ]);
        $transaction = $this->createTransaction($cashier, now());
        $transaction->update(['total_harga' => 48400]);
        DetailTransaksi::create([
            'transaksi_id' => $transaction->id,
            'menu_id' => $menu->id,
            'nama_menu' => 'Kopi Susu',
            'jumlah' => 2,
            'harga_satuan' => 22000,
            'subtotal' => 44000,
        ]);

        $menu->update([
            'nama_menu' => 'Kopi Susu Premium',
            'harga' => 99000,
            'status_ketersediaan' => 'habis',
        ]);
        $menu->delete();

        $history = Transaksi::query()->riwayatTerbaru()->findOrFail($transaction->id);
        $detail = $history->detailTransaksi->sole();

        $this->assertSame(48400, $history->total_harga);
        $this->assertSame('Kopi Susu', $detail->nama_menu);
        $this->assertSame(2, $detail->jumlah);
        $this->assertSame(22000, (int) $detail->harga_satuan);
        $this->assertSame(44000, $detail->subtotal);
        $this->assertSame(22000, intdiv((int) $detail->subtotal, $detail->jumlah));
        $this->assertSame(99000, $detail->menu->harga);
        $this->assertTrue($detail->menu->trashed());

        $this->actingAs($cashier)
            ->get(route('transactions.show', $transaction))
            ->assertOk()
            ->assertSee('Kopi Susu')
            ->assertSee('Rp 22.000')
            ->assertDontSee('Kopi Susu Premium');
    }

    private function createTransaction(User $cashier, CarbonInterface $date): Transaksi
    {
        return Transaksi::create([
            'user_id' => $cashier->id,
            'tanggal' => $date,
            'total_harga' => 24200,
            'metode_pembayaran' => 'cash',
        ]);
    }
}
