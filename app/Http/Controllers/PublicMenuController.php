<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;

class PublicMenuController extends Controller
{
    public function index()
    {
        $menus = Menu::select('id', 'nama_menu', 'kategori', 'harga', 'foto', 'status_ketersediaan')
                    ->orderBy('status_ketersediaan', 'asc')
                    ->orderBy('nama_menu', 'asc')
                    ->get();

        return view('public.menu', compact('menus'));
    }
}