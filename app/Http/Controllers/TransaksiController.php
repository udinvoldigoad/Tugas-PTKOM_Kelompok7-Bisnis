<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Transaksi;
use App\Models\User;
use App\Services\TransactionExportService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class TransaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = $this->historyQuery($request);

        $transaksis = $query->paginate(10)->withQueryString();
        $cashiers = User::query()
            ->whereIn('id', Transaksi::query()->select('user_id'))
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('riwayat.index', compact('transaksis', 'cashiers'));
    }

    public function export(Request $request, TransactionExportService $exportService): BinaryFileResponse|View
    {
        $validated = $request->validate([
            'format' => ['required', 'in:xlsx,pdf'],
            'range' => ['required', 'in:page,all'],
        ]);
        $query = $this->historyQuery($request);
        $transactions = $validated['range'] === 'page'
            ? $query->forPage(max(1, $request->integer('page')), 10)->get()
            : $query->get();

        if ($validated['format'] === 'pdf') {
            return view('riwayat.export-pdf', compact('transactions'));
        }

        $path = $exportService->createXlsx($transactions);

        return response()->download(
            $path,
            'riwayat-transaksi-'.now()->format('Ymd-His').'.xlsx',
            ['Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'],
        )->deleteFileAfterSend();
    }

    private function historyQuery(Request $request): Builder
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

        return $query;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'metode_pembayaran' => ['required', 'in:cash,qris'],
            'idempotency_key' => ['required', 'uuid'],
        ]);

        $existingTransaction = Transaksi::query()
            ->where('user_id', $request->user()->id)
            ->where('idempotency_key', $validated['idempotency_key'])
            ->with('detailTransaksi')
            ->first();

        if ($existingTransaction) {
            $request->session()->forget('cart');

            return $this->transactionResponse($request, $existingTransaction, replayed: true);
        }

        $cart = $request->session()->get('cart', []);

        if ($cart === []) {
            throw ValidationException::withMessages([
                'cart' => 'Keranjang masih kosong.',
            ]);
        }

        try {
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
                        'nama_menu' => $menu->nama_menu,
                        'jumlah' => $quantity,
                        'harga_satuan' => $menu->harga,
                        'subtotal' => $itemSubtotal,
                    ];
                }

                $transaction = Transaksi::create([
                    'user_id' => $request->user()->id,
                    'idempotency_key' => $validated['idempotency_key'],
                    'tanggal' => now(),
                    'total_harga' => $subtotal + (int) round($subtotal * 0.1),
                    'metode_pembayaran' => $validated['metode_pembayaran'],
                ]);
                $transaction->detailTransaksi()->createMany($details);

                return $transaction->load('detailTransaksi');
            });
        } catch (UniqueConstraintViolationException $exception) {
            $transaksi = Transaksi::query()
                ->where('user_id', $request->user()->id)
                ->where('idempotency_key', $validated['idempotency_key'])
                ->with('detailTransaksi')
                ->first();

            if (! $transaksi) {
                throw $exception;
            }

            $request->session()->forget('cart');

            return $this->transactionResponse($request, $transaksi, replayed: true);
        }

        $request->session()->forget('cart');

        return $this->transactionResponse($request, $transaksi, replayed: false);
    }

    private function transactionResponse(
        Request $request,
        Transaksi $transaksi,
        bool $replayed,
    ): JsonResponse|RedirectResponse {
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Transaksi berhasil disimpan.',
                'transaksi' => $transaksi,
                'replayed' => $replayed,
            ], $replayed ? 200 : 201);
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
}
