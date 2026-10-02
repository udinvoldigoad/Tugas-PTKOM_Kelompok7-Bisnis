<?php

namespace Tests\Feature;

use App\Models\Menu;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MenuManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_view_the_menu_management_page(): void
    {
        $user = User::factory()->create();
        $menu = $this->createMenu(['nama_menu' => 'Kopi Tubruk']);

        $this->actingAs($user)->get(route('menu.index'))
            ->assertOk()
            ->assertViewHas('menus', fn ($menus): bool => $menus->contains($menu));
    }

    public function test_unavailable_menus_are_listed_after_available_menus(): void
    {
        $user = User::factory()->create();
        $unavailableMenu = $this->createMenu([
            'nama_menu' => 'Americano Habis',
            'status_ketersediaan' => 'habis',
        ]);
        $availableMenu = $this->createMenu([
            'nama_menu' => 'Zuppa Soup',
            'status_ketersediaan' => 'tersedia',
        ]);

        $this->actingAs($user)->get(route('menu.index'))
            ->assertOk()
            ->assertViewHas('menus', function ($menus) use ($availableMenu, $unavailableMenu): bool {
                return $menus->pluck('id')->all() === [$availableMenu->id, $unavailableMenu->id];
            });
    }

    public function test_guest_cannot_manage_menus(): void
    {
        $menu = $this->createMenu();

        $this->get(route('menu.index'))->assertRedirect(route('login'));
        $this->post(route('menu.store'))->assertRedirect(route('login'));
        $this->patch(route('menu.update', $menu))->assertRedirect(route('login'));
        $this->delete(route('menu.destroy', $menu))->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_create_a_menu_with_a_photo(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();

        $response = $this->actingAs($user)->withHeader('Accept', 'application/json')->post(route('menu.store'), [
            'nama_menu' => 'Es Kopi Susu',
            'kategori' => 'Kopi',
            'harga' => 22000,
            'status_ketersediaan' => 'tersedia',
            'foto' => UploadedFile::fake()->image('kopi.webp'),
        ]);

        $response->assertCreated()->assertJsonPath('menu.nama_menu', 'Es Kopi Susu');
        $menu = Menu::where('nama_menu', 'Es Kopi Susu')->firstOrFail();

        $this->assertSame('Kopi', $menu->kategori);
        $this->assertEquals(22000, $menu->harga);
        Storage::disk('public')->assertExists($menu->foto);
    }

    public function test_menu_data_and_photo_are_validated(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();

        $this->actingAs($user)->from(route('menu.index'))->post(route('menu.store'), [
            'nama_menu' => '',
            'kategori' => 'Tidak Valid',
            'harga' => 0,
            'status_ketersediaan' => 'arsip',
            'foto' => UploadedFile::fake()->create('menu.pdf', 10, 'application/pdf'),
        ])->assertRedirect(route('menu.index'))
            ->assertSessionHasErrors(['nama_menu', 'kategori', 'harga', 'status_ketersediaan', 'foto']);

        $this->assertDatabaseCount('menus', 0);
        $this->assertSame([], Storage::disk('public')->allFiles());
    }

    public function test_authenticated_user_can_update_status_and_replace_photo(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('menus/old.jpg', 'old-photo');
        $user = User::factory()->create();
        $menu = $this->createMenu(['foto' => 'menus/old.jpg']);

        $this->actingAs($user)->withHeader('Accept', 'application/json')->patch(route('menu.update', $menu), [
            'nama_menu' => 'Kopi Susu Aren',
            'kategori' => 'Non-Kopi',
            'harga' => 25000,
            'status_ketersediaan' => 'habis',
            'foto' => UploadedFile::fake()->image('new.png'),
        ])->assertOk()->assertJsonPath('menu.status_ketersediaan', 'habis');

        $menu->refresh();

        $this->assertSame('Kopi Susu Aren', $menu->nama_menu);
        $this->assertSame('habis', $menu->status_ketersediaan);
        Storage::disk('public')->assertMissing('menus/old.jpg');
        Storage::disk('public')->assertExists($menu->foto);
    }

    public function test_deleting_a_menu_uses_soft_delete_and_keeps_its_photo(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('menus/menu.jpg', 'photo');
        $user = User::factory()->create();
        $menu = $this->createMenu(['foto' => 'menus/menu.jpg']);

        $this->actingAs($user)->withHeader('Accept', 'application/json')->delete(route('menu.destroy', $menu))
            ->assertOk();

        $this->assertSoftDeleted('menus', ['id' => $menu->id]);
        Storage::disk('public')->assertExists('menus/menu.jpg');

        $this->actingAs($user)->withHeader('Accept', 'application/json')->post(route('menu.restore', $menu->id))
            ->assertOk()
            ->assertJsonPath('menu.id', $menu->id);

        $this->assertNotSoftDeleted('menus', ['id' => $menu->id]);
    }

    public function test_authenticated_user_can_remove_a_menu_photo(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('menus/menu.jpg', 'photo');
        $user = User::factory()->create();
        $menu = $this->createMenu(['foto' => 'menus/menu.jpg']);

        $this->actingAs($user)->patch(route('menu.update', $menu), [
            'nama_menu' => $menu->nama_menu,
            'kategori' => $menu->kategori,
            'harga' => $menu->harga,
            'status_ketersediaan' => $menu->status_ketersediaan,
            'hapus_foto' => true,
        ])->assertRedirect(route('menu.index'));

        $this->assertNull($menu->fresh()->foto);
        Storage::disk('public')->assertMissing('menus/menu.jpg');
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    private function createMenu(array $attributes = []): Menu
    {
        return Menu::create(array_merge([
            'nama_menu' => 'Americano',
            'kategori' => 'Kopi',
            'harga' => 20000,
            'status_ketersediaan' => 'tersedia',
        ], $attributes));
    }
}
