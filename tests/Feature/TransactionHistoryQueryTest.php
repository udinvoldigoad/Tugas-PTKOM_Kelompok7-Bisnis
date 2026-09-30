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
