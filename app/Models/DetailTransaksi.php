<?php

namespace App\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Collection;

class DetailTransaksi extends Model
{
    // Memastikan subtotal (harga saat transaksi) dan menu_id bisa disimpan
    protected $fillable = ['transaksi_id', 'menu_id', 'jumlah', 'subtotal'];

    // Relasi Belongs-To ke Transaksi
    public function transaksi(): BelongsTo
    {
        return $this->belongsTo(Transaksi::class);
    }

    // Relasi Belongs-To ke Menu
    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class)->withTrashed();
    }

    /**
     * Ambil menu terlaris berdasarkan jumlah item yang terjual pada hari laporan.
     *
     * @return Collection<int, self>
     */
    public static function menuTerlarisHariIni(
        ?CarbonInterface $today = null,
        int $limit = 5,
        ?int $userId = null,
    ): Collection {
        $today ??= now(config('app.timezone'));
        $startOfDay = $today->copy()->timezone(config('app.timezone'))->startOfDay();
        $endOfDay = $startOfDay->copy()->addDay();

        return static::query()
            ->join('transaksis', 'transaksis.id', '=', 'detail_transaksis.transaksi_id')
            ->join('menus', 'menus.id', '=', 'detail_transaksis.menu_id')
            ->where('transaksis.tanggal', '>=', $startOfDay)
            ->where('transaksis.tanggal', '<', $endOfDay)
            ->when($userId, fn ($query) => $query->where('transaksis.user_id', $userId))
            ->select([
                'detail_transaksis.menu_id',
                'menus.nama_menu',
                'menus.kategori',
            ])
            ->selectRaw('SUM(detail_transaksis.jumlah) as total_terjual')
            ->selectRaw('SUM(detail_transaksis.subtotal) as subtotal_penjualan')
            ->groupBy('detail_transaksis.menu_id', 'menus.nama_menu', 'menus.kategori')
            ->orderByDesc('total_terjual')
            ->orderByDesc('subtotal_penjualan')
            ->orderBy('detail_transaksis.menu_id')
            ->limit($limit)
            ->get();
    }
}
