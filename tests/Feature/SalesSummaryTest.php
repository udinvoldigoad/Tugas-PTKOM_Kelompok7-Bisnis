<?php

namespace Tests\Feature;

use App\Models\DetailTransaksi;
use App\Models\Menu;
use App\Models\Transaksi;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SalesSummaryTest extends TestCase
{
    use RefreshDatabase;

    public function test_total_penjualan_hari_ini_only_counts_transactions_from_current_report_day(): void
    {
        $cashier = User::factory()->create();

        Transaksi::create([
            'user_id' => $cashier->id,
            'tanggal' => CarbonImmutable::parse('2026-10-02 00:00:00', 'Asia/Jakarta'),
            'total_harga' => 24200,
            'metode_pembayaran' => 'cash',
        ]);

        Transaksi::create([
            'user_id' => $cashier->id,
            'tanggal' => CarbonImmutable::parse('2026-10-02 23:59:59', 'Asia/Jakarta'),
            'total_harga' => 48400,
            'metode_pembayaran' => 'qris',
        ]);

        Transaksi::create([
            'user_id' => $cashier->id,
            'tanggal' => CarbonImmutable::parse('2026-10-01 23:59:59', 'Asia/Jakarta'),
            'total_harga' => 999999,
            'metode_pembayaran' => 'cash',
        ]);

        $this->assertSame(
            72600,
            Transaksi::totalPenjualanHariIni(CarbonImmutable::parse('2026-10-02 12:00:00', 'Asia/Jakarta'))
        );
    }

    public function test_dashboard_shows_total_penjualan_hari_ini(): void
    {
        $cashier = User::factory()->create();

        Transaksi::create([
            'user_id' => $cashier->id,
            'tanggal' => now('Asia/Jakarta'),
            'total_harga' => 24200,
            'metode_pembayaran' => 'cash',
        ]);

        $this->actingAs($cashier)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Penjualan Hari Ini')
            ->assertSee('Rp 24.200');
    }

    public function test_sales_summary_is_empty_when_there_are_no_transactions_today(): void
    {
        $cashier = User::factory()->create();
        $today = CarbonImmutable::parse('2026-10-02 12:00:00', 'Asia/Jakarta');

        $this->assertSame(0, Transaksi::totalPenjualanHariIni($today));
        $this->assertTrue(DetailTransaksi::menuTerlarisHariIni($today)->isEmpty());

        $this->travelTo($today);

        $this->actingAs($cashier)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Rp 0')
            ->assertSee('Belum ada penjualan');
    }

    public function test_menu_terlaris_is_ranked_by_sold_quantity_instead_of_transaction_count(): void
    {
        $cashier = User::factory()->create();
        $americano = Menu::create([
            'nama_menu' => 'Americano',
            'kategori' => 'kopi',
            'harga' => 20000,
            'status_ketersediaan' => 'tersedia',
        ]);
        $cappuccino = Menu::create([
            'nama_menu' => 'Cappuccino',
            'kategori' => 'kopi',
            'harga' => 25000,
            'status_ketersediaan' => 'tersedia',
        ]);

        $firstTransaction = Transaksi::create([
            'user_id' => $cashier->id,
            'tanggal' => CarbonImmutable::parse('2026-10-02 09:00:00', 'Asia/Jakarta'),
            'total_harga' => 20000,
            'metode_pembayaran' => 'cash',
        ]);
        $secondTransaction = Transaksi::create([
            'user_id' => $cashier->id,
            'tanggal' => CarbonImmutable::parse('2026-10-02 10:00:00', 'Asia/Jakarta'),
            'total_harga' => 20000,
            'metode_pembayaran' => 'cash',
        ]);
        $thirdTransaction = Transaksi::create([
            'user_id' => $cashier->id,
            'tanggal' => CarbonImmutable::parse('2026-10-02 11:00:00', 'Asia/Jakarta'),
            'total_harga' => 75000,
            'metode_pembayaran' => 'qris',
        ]);
        $previousDayTransaction = Transaksi::create([
            'user_id' => $cashier->id,
            'tanggal' => CarbonImmutable::parse('2026-10-01 23:59:59', 'Asia/Jakarta'),
            'total_harga' => 200000,
            'metode_pembayaran' => 'cash',
        ]);

        DetailTransaksi::create([
            'transaksi_id' => $firstTransaction->id,
            'menu_id' => $americano->id,
            'jumlah' => 1,
            'subtotal' => 20000,
        ]);
        DetailTransaksi::create([
            'transaksi_id' => $secondTransaction->id,
            'menu_id' => $americano->id,
            'jumlah' => 1,
            'subtotal' => 20000,
        ]);
        DetailTransaksi::create([
            'transaksi_id' => $thirdTransaction->id,
            'menu_id' => $cappuccino->id,
            'jumlah' => 3,
            'subtotal' => 75000,
        ]);
        DetailTransaksi::create([
            'transaksi_id' => $previousDayTransaction->id,
            'menu_id' => $americano->id,
            'jumlah' => 10,
            'subtotal' => 200000,
        ]);

        $ranking = DetailTransaksi::menuTerlarisHariIni(
            CarbonImmutable::parse('2026-10-02 12:00:00', 'Asia/Jakarta')
        );

        $this->assertSame('Cappuccino', $ranking->first()->nama_menu);
        $this->assertSame(3, (int) $ranking->first()->total_terjual);
        $this->assertSame('Americano', $ranking->get(1)->nama_menu);
        $this->assertSame(2, (int) $ranking->get(1)->total_terjual);
    }

    public function test_menu_terlaris_uses_subtotal_then_menu_id_to_break_quantity_ties(): void
    {
        $cashier = User::factory()->create();
        $lowerIdMenu = Menu::create([
            'nama_menu' => 'Kopi A',
            'kategori' => 'kopi',
            'harga' => 20000,
            'status_ketersediaan' => 'tersedia',
        ]);
        $higherSubtotalMenu = Menu::create([
            'nama_menu' => 'Kopi B',
            'kategori' => 'kopi',
            'harga' => 25000,
            'status_ketersediaan' => 'tersedia',
        ]);
        $higherIdMenu = Menu::create([
            'nama_menu' => 'Kopi C',
            'kategori' => 'kopi',
            'harga' => 20000,
            'status_ketersediaan' => 'tersedia',
        ]);
        $transaction = Transaksi::create([
            'user_id' => $cashier->id,
            'tanggal' => CarbonImmutable::parse('2026-10-02 12:00:00', 'Asia/Jakarta'),
            'total_harga' => 130000,
            'metode_pembayaran' => 'cash',
        ]);

        foreach ([
            [$lowerIdMenu, 40000],
            [$higherSubtotalMenu, 50000],
            [$higherIdMenu, 40000],
        ] as [$menu, $subtotal]) {
            DetailTransaksi::create([
                'transaksi_id' => $transaction->id,
                'menu_id' => $menu->id,
                'jumlah' => 2,
                'subtotal' => $subtotal,
            ]);
        }

        $ranking = DetailTransaksi::menuTerlarisHariIni(
            CarbonImmutable::parse('2026-10-02 15:00:00', 'Asia/Jakarta')
        );

        $this->assertSame(
            ['Kopi B', 'Kopi A', 'Kopi C'],
            $ranking->pluck('nama_menu')->all()
        );
    }
}
