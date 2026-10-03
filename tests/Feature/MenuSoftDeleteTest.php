<?php

namespace Tests\Feature;

use App\Models\DetailTransaksi;
use App\Models\Menu;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MenuSoftDeleteTest extends TestCase
{
    use RefreshDatabase;

    public function test_deleting_a_menu_keeps_it_available_to_existing_transaction_details(): void
    {
        $user = User::factory()->create();
        $menu = Menu::create([
            'nama_menu' => 'Kopi Susu',
            'kategori' => 'Kopi',
            'harga' => 22000,
            'status_ketersediaan' => 'tersedia',
        ]);
        $transaksi = Transaksi::create([
            'user_id' => $user->id,
            'tanggal' => now(),
            'total_harga' => 22000,
        ]);
        $detail = DetailTransaksi::create([
            'transaksi_id' => $transaksi->id,
            'menu_id' => $menu->id,
            'jumlah' => 1,
            'subtotal' => 22000,
        ]);

        $menu->delete();

        $this->assertSoftDeleted('menus', ['id' => $menu->id]);
        $this->assertNull(Menu::find($menu->id));
        $this->assertSame($menu->id, $detail->fresh()->menu->id);
        $this->assertSame('Kopi Susu', $detail->fresh()->menu->nama_menu);
    }
}
