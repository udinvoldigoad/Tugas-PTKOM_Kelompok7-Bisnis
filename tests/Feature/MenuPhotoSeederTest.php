<?php

namespace Tests\Feature;

use App\Models\Menu;
use Database\Seeders\MenuPhotoSeeder;
use Database\Seeders\MenuSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MenuPhotoSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_bundled_photo_is_copied_to_the_public_disk_and_linked_to_the_menu(): void
    {
        Storage::fake('public');
        $this->seed(MenuSeeder::class);

        $this->seed(MenuPhotoSeeder::class);

        $menu = Menu::where('nama_menu', 'Cappuccino')->firstOrFail();

        $this->assertNotNull($menu->foto);
        $this->assertStringStartsWith('menus/', $menu->foto);
        Storage::disk('public')->assertExists($menu->foto);
    }

    public function test_menus_are_matched_by_name_so_production_ids_do_not_matter(): void
    {
        Storage::fake('public');
        Menu::create([
            'nama_menu' => 'Cappuccino',
            'kategori' => 'Kopi',
            'harga' => 25000,
            'status_ketersediaan' => 'tersedia',
        ]);
        $other = Menu::create([
            'nama_menu' => 'Menu Tanpa Foto Bawaan',
            'kategori' => 'Kopi',
            'harga' => 25000,
            'status_ketersediaan' => 'tersedia',
        ]);

        $this->seed(MenuPhotoSeeder::class);

        $this->assertNotNull(Menu::where('nama_menu', 'Cappuccino')->firstOrFail()->foto);
        $this->assertNull($other->refresh()->foto);
    }

    public function test_running_the_seeder_twice_keeps_one_file_per_photo(): void
    {
        Storage::fake('public');
        $this->seed(MenuSeeder::class);

        $this->seed(MenuPhotoSeeder::class);
        $afterFirstRun = Storage::disk('public')->files('menus');

        $this->seed(MenuPhotoSeeder::class);

        $this->assertSame($afterFirstRun, Storage::disk('public')->files('menus'));
    }

    public function test_photo_uploaded_by_staff_is_never_replaced_by_a_placeholder(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('menus/foto-asli.webp', 'binary');
        $menu = Menu::create([
            'nama_menu' => 'Matcha Latte',
            'kategori' => 'Non-Kopi',
            'harga' => 26000,
            'foto' => 'menus/foto-asli.webp',
            'status_ketersediaan' => 'tersedia',
        ]);

        $this->seed(MenuPhotoSeeder::class);

        $this->assertSame('menus/foto-asli.webp', $menu->refresh()->foto);
        Storage::disk('public')->assertExists('menus/foto-asli.webp');
    }

    public function test_placeholder_file_is_not_copied_when_no_menu_needs_it(): void
    {
        Storage::fake('public');
        Menu::create([
            'nama_menu' => 'Matcha Latte',
            'kategori' => 'Non-Kopi',
            'harga' => 26000,
            'foto' => 'menus/foto-asli.webp',
            'status_ketersediaan' => 'tersedia',
        ]);

        $this->seed(MenuPhotoSeeder::class);

        $this->assertSame([], Storage::disk('public')->files('menus'));
    }

    public function test_every_seeded_menu_gets_a_photo_that_is_really_bundled(): void
    {
        Storage::fake('public');
        $this->seed(MenuSeeder::class);

        $this->seed(MenuPhotoSeeder::class);

        $menus = Menu::all();
        $this->assertNotEmpty($menus);

        foreach ($menus as $menu) {
            $this->assertNotNull($menu->foto, "Menu {$menu->nama_menu} tidak punya foto.");
            $this->assertFileExists(database_path('seeders/menu-photos/'.basename($menu->foto)));
        }
    }
}
