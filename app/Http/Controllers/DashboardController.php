<?php

namespace App\Http\Controllers;

use App\Models\DetailTransaksi;
use App\Models\Transaksi;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $user = $request->user();
        $today = now(config('app.timezone'));
        $transactions = Transaksi::query()
            ->padaTanggalLaporan($today)
            ->where('user_id', $user->id)
            ->with('detailTransaksi:id,transaksi_id,jumlah')
            ->get(['id', 'tanggal', 'total_harga']);

        $salesByHour = $transactions
            ->groupBy(fn (Transaksi $transaction): int => Carbon::parse($transaction->tanggal)->hour)
            ->map(fn ($hourTransactions): int => (int) $hourTransactions->sum(
                fn (Transaksi $transaction): int => (int) $transaction->detailTransaksi->sum('jumlah')
            ));

        $hoursWithSales = $salesByHour->filter(fn (int $cups): bool => $cups > 0)->keys();
        $chartStartHour = min(8, (int) ($hoursWithSales->min() ?? 8));
        $chartEndHour = max(21, (int) ($hoursWithSales->max() ?? 21));

        $hourlySales = collect(range($chartStartHour, $chartEndHour))->map(fn (int $hour): array => [
            'hour' => sprintf('%02d:00', $hour),
            'cups' => (int) $salesByHour->get($hour, 0),
        ]);
        $busiestHour = $hourlySales->sortByDesc('cups')->first();
        $dailyTarget = (int) config('sales.daily_transaction_target');
        $totalTransactions = $transactions->count();

        return view('dashboard', [
            'user' => $user,
            'periodLabel' => $today->copy()->locale('id')->translatedFormat('d F Y'),
            'totalPenjualanHariIni' => Transaksi::totalPenjualanHariIni($today, $user->id),
            'totalTransactions' => $totalTransactions,
            'dailyTarget' => $dailyTarget,
            'targetPercentage' => min(100, (int) round(($totalTransactions / $dailyTarget) * 100)),
            'menuTerlarisHariIni' => DetailTransaksi::menuTerlarisHariIni($today, userId: $user->id),
            'hourlySales' => $hourlySales,
            'chartMaximum' => max(5, (int) $hourlySales->max('cups')),
            'busiestHour' => ($busiestHour['cups'] ?? 0) > 0 ? $busiestHour : null,
        ]);
    }
}
