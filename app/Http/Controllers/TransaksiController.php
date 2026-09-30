<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class TransaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = Transaksi::query()->riwayatTerbaru();
        $search = trim((string) $request->query('q', ''));
        $paymentMethod = (string) $request->query('payment', '');
        $cashierId = $request->integer('cashier');
        $period = (string) $request->query('period', '');

        if ($search !== '') {
            $transactionId = (int) preg_replace('/\D/', '', $search);

            $query->where(function ($query) use ($search, $transactionId): void {
                if ($transactionId > 0) {
                    $query->whereKey($transactionId);
                }

                $query->orWhereHas('user', function ($query) use ($search): void {
                    $query->where('name', 'like', "%{$search}%");
                });
            });
        }

        if (in_array($paymentMethod, ['cash', 'qris'], true)) {
            $query->where('metode_pembayaran', $paymentMethod);
        }

        if ($cashierId > 0) {
            $query->where('user_id', $cashierId);
        }

        match ($period) {
            'today' => $query->whereDate('tanggal', today()),
            'yesterday' => $query->whereDate('tanggal', today()->subDay()),
            'week' => $query->whereBetween('tanggal', [now()->startOfWeek(), now()->endOfWeek()]),
            'month' => $query->whereYear('tanggal', now()->year)->whereMonth('tanggal', now()->month),
            'custom' => $query
                ->when($request->date('date_from'), fn ($query, $date) => $query->whereDate('tanggal', '>=', $date))
                ->when($request->date('date_to'), fn ($query, $date) => $query->whereDate('tanggal', '<=', $date)),
            default => null,
        };

        $transaksis = $query->paginate(10)->withQueryString();
        $cashiers = User::query()
            ->whereIn('id', Transaksi::query()->select('user_id'))
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('riwayat.index', compact('transaksis', 'cashiers'));
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
    public function show(Transaksi $transaksi): View
    {
        $transaksi->load(['user:id,name', 'detailTransaksi.menu']);

        return view('riwayat.show', compact('transaksi'));
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
