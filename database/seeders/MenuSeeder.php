<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        $menus = [
            ['nama_menu' => 'Espresso',        'kategori' => 'Kopi',    'harga' => 18000, 'status_ketersediaan' => 'tersedia'],
            ['nama_menu' => 'Americano',       'kategori' => 'Kopi',    'harga' => 20000, 'status_ketersediaan' => 'tersedia'],
            ['nama_menu' => 'Cappuccino',      'kategori' => 'Kopi',    'harga' => 25000, 'status_ketersediaan' => 'tersedia'],
            ['nama_menu' => 'Kopi Susu Gula Aren', 'kategori' => 'Kopi', 'harga' => 22000, 'status_ketersediaan' => 'tersedia'],
            ['nama_menu' => 'Matcha Latte',    'kategori' => 'Non-Kopi', 'harga' => 26000, 'status_ketersediaan' => 'tersedia'],
            ['nama_menu' => 'Lemon Tea',       'kategori' => 'Non-Kopi', 'harga' => 15000, 'status_ketersediaan' => 'habis'],
            ['nama_menu' => 'Croissant',       'kategori' => 'Makanan', 'harga' => 23000, 'status_ketersediaan' => 'tersedia'],
            ['nama_menu' => 'Kentang Goreng',  'kategori' => 'Makanan', 'harga' => 20000, 'status_ketersediaan' => 'tersedia'],
            ['nama_menu' => 'Nasi Goreng Kafe','kategori' => 'Makanan', 'harga' => 32000, 'status_ketersediaan' => 'tersedia'],
            ['nama_menu' => 'Cheesecake',      'kategori' => 'Dessert', 'harga' => 28000, 'status_ketersediaan' => 'tersedia'],
        ];

        foreach ($menus as $menu) {
            Menu::updateOrCreate(['nama_menu' => $menu['nama_menu']], $menu);
        }
    }
}