<?php

namespace Tests\Feature;

use App\Models\DetailTransaksi;
use App\Models\Menu;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use ZipArchive;

class TransactionExportTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_export_transaction_history(): void
    {
        $this->get(route('transactions.export', ['format' => 'xlsx', 'range' => 'all']))
            ->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_export_filtered_history_as_xlsx(): void
    {
        $cashier = User::factory()->create(['name' => 'Kasir Export']);
        $transaction = $this->createTransaction($cashier, 'cash', 24200);

        $response = $this->actingAs($cashier)->get(route('transactions.export', [
            'format' => 'xlsx',
            'range' => 'all',
            'payment' => 'cash',
        ]));

        $response->assertOk()->assertDownload();
        $this->assertSame(
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            $response->headers->get('content-type'),
        );

        $zip = new ZipArchive;
        $this->assertTrue($zip->open($response->baseResponse->getFile()->getPathname()) === true);
        $worksheet = $zip->getFromName('xl/worksheets/sheet1.xml');
        $zip->close();

        $this->assertIsString($worksheet);
        $this->assertStringContainsString(sprintf('TRX-%03d', $transaction->id), $worksheet);
        $this->assertStringContainsString('Kasir Export', $worksheet);
    }

    public function test_pdf_print_view_respects_active_filters(): void
    {
        $cashier = User::factory()->create(['name' => 'Kasir Filter']);
        $included = $this->createTransaction($cashier, 'qris', 33000);
        $excluded = $this->createTransaction($cashier, 'cash', 22000);

        $this->actingAs($cashier)->get(route('transactions.export', [
            'format' => 'pdf',
            'range' => 'all',
            'payment' => 'qris',
        ]))
            ->assertOk()
            ->assertSee(sprintf('TRX-%03d', $included->id))
            ->assertDontSee(sprintf('TRX-%03d', $excluded->id))
            ->assertSee('Simpan / Cetak PDF');
    }

    private function createTransaction(User $cashier, string $paymentMethod, int $total): Transaksi
    {
        $menu = Menu::create([
            'nama_menu' => 'Menu Export '.$paymentMethod,
            'kategori' => 'Kopi',
            'harga' => 20000,
            'status_ketersediaan' => 'tersedia',
        ]);
        $transaction = Transaksi::create([
            'user_id' => $cashier->id,
            'tanggal' => now(),
            'total_harga' => $total,
            'metode_pembayaran' => $paymentMethod,
        ]);
        DetailTransaksi::create([
            'transaksi_id' => $transaction->id,
            'menu_id' => $menu->id,
            'nama_menu' => $menu->nama_menu,
            'jumlah' => 1,
            'harga_satuan' => 20000,
            'subtotal' => 20000,
        ]);

        return $transaction;
    }
}
