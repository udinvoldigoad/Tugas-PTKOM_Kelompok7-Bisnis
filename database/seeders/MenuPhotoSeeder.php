<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class MenuPhotoSeeder extends Seeder
{
    /**
     * Placeholder photos shipped with the repository, keyed by menu name.
     *
     * The files live in database/seeders/menu-photos so they survive a clone;
     * storage/app/public is git-ignored and never reaches another machine.
     *
     * @var array<string, string>
     */
    private const PHOTOS = [
        'Espresso' => '3c5f68aa002f31ae22d500a69d20e06782d9e609.jpg',
        'Americano' => '13c73c3c83e3df8b7cbabad4d667c4ff76da8e1f.jpg',
        'Cappuccino' => '61ca4dab482f09911e81a629b97d54bf950a95a8.jpg',
        'Kopi Susu Gula Aren' => 'acd80cbd456028b09e1a7b61e7f59ea0727846ec.jpg',
        'Matcha Latte' => '7cd40e834710047508462d986b394e25f43edcde.jpg',
        'Lemon Tea' => '940fead27d6eec4640a0428f8cb9b06d6c1c825b.jpg',
        'Croissant' => 'cf821ca1d1bc1b77587868d72df58888f5fc66b4.jpg',
        'Kentang Goreng' => '97d9a7d1ae6ed545b3ff121ccff5b8d6d59f3bde.jpg',
        'Nasi Goreng Kafe' => '31fd8a2e4395d792c4f1e185ecfc8853e3e8a3f8.jpg',
        'Cheesecake' => '046a3d997a83a901c18bab3a24fb4d17ba8a1993.jpg',
        'ekstra joss' => 'ca6579d481a09197194e9a27772c9ee1c64bfe59.jpg',
    ];

    /**
     * Give menus that have no photo yet one of the bundled placeholders.
     *
     * Menus are matched by name because ids differ between environments. A menu
     * that already has a photo is left alone: on a live site that photo was
     * uploaded by staff and must not be replaced by dummy data. The stored
     * filename is reused so repeated runs never pile up duplicate files.
     */
    public function run(): void
    {
        foreach (self::PHOTOS as $menuName => $filename) {
            $menusWithoutPhoto = Menu::withTrashed()
                ->where('nama_menu', $menuName)
                ->whereNull('foto');

            if ($menusWithoutPhoto->doesntExist()) {
                continue;
            }

            $sourcePath = database_path('seeders/menu-photos/'.$filename);

            if (! is_file($sourcePath)) {
                $this->command?->warn("Foto tidak ditemukan, dilewati: {$filename}");

                continue;
            }

            $storedPath = 'menus/'.$filename;

            if (! Storage::disk('public')->exists($storedPath)) {
                Storage::disk('public')->put($storedPath, (string) file_get_contents($sourcePath));
            }

            $menusWithoutPhoto->update(['foto' => $storedPath]);
        }
    }
}
