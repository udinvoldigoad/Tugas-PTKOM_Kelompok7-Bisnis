<?php

namespace App\Http\Controllers;

use App\Models\DetailTransaksi;
use App\Models\Menu;
use App\Models\Transaksi;
use Illuminate\Http\Request;

class TransaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $transaksis = Transaksi::with(['details.menu', 'user'])->latest()->paginate(15);

        return view('transaksi.index', compact('transaksis'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|exists:menus,id',
            'items.*.qty' => 'required|integer|min:1',
        ]);

        $total = 0;
        foreach ($request->items as $item) {
            $menu = Menu::find($item['id']);
            $total += $menu->harga * $item['qty'];
        }

        $transaksi = Transaksi::create([
            'user_id' => $request->user()->id,
            'tanggal' => now(),
            'total_harga' => $total,
        ]);

        foreach ($request->items as $item) {
            $menu = Menu::find($item['id']);
            DetailTransaksi::create([
                'transaksi_id' => $transaksi->id,
                'menu_id' => $menu->id,
                'jumlah' => $item['qty'],
                'subtotal' => $menu->harga * $item['qty'],
            ]);
        }

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'transaksi' => $transaksi->load('details.menu')]);
        }

        return redirect()->back()->with('status', 'transaksi-berhasil');
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaksi $transaksi)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaksi $transaksi)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transaksi $transaksi)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaksi $transaksi)
    {
        //
    }
}
