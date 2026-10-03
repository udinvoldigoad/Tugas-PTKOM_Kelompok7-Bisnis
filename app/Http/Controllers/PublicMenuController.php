<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\View\View;

class PublicMenuController extends Controller
{
    public function index(): View
    {
        $menus = Menu::query()
            ->select('id', 'nama_menu', 'kategori', 'harga', 'foto', 'status_ketersediaan')
            ->orderByRaw("CASE WHEN status_ketersediaan = 'tersedia' THEN 0 ELSE 1 END")
            ->orderBy('nama_menu')
            ->get();

        return view('public.menu', ['menus' => $menus]);
    }
}
