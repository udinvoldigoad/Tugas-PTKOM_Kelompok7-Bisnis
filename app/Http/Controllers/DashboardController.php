<?php

namespace App\Http\Controllers;

use App\Models\DetailTransaksi;
use App\Models\Transaksi;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        return view('dashboard', [
            'user' => $request->user(),
            'totalPenjualanHariIni' => Transaksi::totalPenjualanHariIni(),
            'menuTerlarisHariIni' => DetailTransaksi::menuTerlarisHariIni(),
        ]);
    }
}
