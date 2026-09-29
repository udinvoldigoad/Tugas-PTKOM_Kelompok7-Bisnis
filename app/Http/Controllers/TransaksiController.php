<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Transaksi;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class TransaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'metode_pembayaran' => ['required', 'in:cash,qris'],
        ]);
        $cart = $request->session()->get('cart', []);

        if ($cart === []) {
            throw ValidationException::withMessages([
                'cart' => 'Keranjang masih kosong.',
            ]);
        }

        $transaksi = DB::transaction(function () use ($cart, $request, $validated): Transaksi {
            $menus = Menu::query()
                ->whereKey(array_keys($cart))
                ->lockForUpdate()
                ->get()
                ->keyBy('id');
            $details = [];
            $subtotal = 0;

            foreach ($cart as $menuId => $cartItem) {
                $menu = $menus->get((int) $menuId);

                if (! $menu) {
                    throw ValidationException::withMessages([
                        'cart' => 'Salah satu menu sudah tidak tersedia.',
                    ]);
                }

                if ($menu->status_ketersediaan !== 'tersedia') {
                    throw ValidationException::withMessages([
                        'cart' => "{$menu->nama_menu} sedang habis.",
                    ]);
                }

                $quantity = max(1, (int) ($cartItem['jumlah'] ?? 1));
                $itemSubtotal = $quantity * (int) $menu->harga;
                $subtotal += $itemSubtotal;
                $details[] = [
                    'menu_id' => $menu->id,
                    'jumlah' => $quantity,
                    'subtotal' => $itemSubtotal,
                ];
            }

            $transaction = Transaksi::create([
                'user_id' => $request->user()->id,
                'tanggal' => now(),
                'total_harga' => $subtotal + (int) round($subtotal * 0.1),
                'metode_pembayaran' => $validated['metode_pembayaran'],
            ]);
            $transaction->detailTransaksi()->createMany($details);

            return $transaction->load('detailTransaksi');
        });

        $request->session()->forget('cart');

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Transaksi berhasil disimpan.',
                'transaksi' => $transaksi,
            ], 201);
        }

        return redirect()->route('menu.index')->with('success', 'Transaksi berhasil disimpan.');
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
