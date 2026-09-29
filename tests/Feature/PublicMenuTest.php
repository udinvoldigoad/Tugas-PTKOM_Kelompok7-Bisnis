<?php

namespace Tests\Feature;

use App\Models\Menu;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicMenuTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_menu_hides_deleted_items_and_marks_unavailable_items(): void
    {
        $availableMenu = $this->createMenu([
            'nama_menu' => 'Americano',
            'status_ketersediaan' => 'tersedia',
        ]);
        $unavailableMenu = $this->createMenu([
            'nama_menu' => 'Lemon Tea',
            'status_ketersediaan' => 'habis',
        ]);
        $deletedMenu = $this->createMenu([
            'nama_menu' => 'Menu Terhapus',
        ]);
        $deletedMenu->delete();

        $this->get(route('public.menu'))
            ->assertOk()
            ->assertSeeInOrder([$availableMenu->nama_menu, $unavailableMenu->nama_menu])
            ->assertSee('Habis')
            ->assertDontSee($deletedMenu->nama_menu);
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
