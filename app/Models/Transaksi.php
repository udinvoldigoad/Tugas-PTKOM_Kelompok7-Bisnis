<?php

namespace App\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Transaksi extends Model
{
    // Mengizinkan kolom diisi secara massal
    protected $fillable = ['user_id', 'tanggal', 'total_harga', 'metode_pembayaran'];

    // Relasi One-to-Many ke DetailTransaksi
    public function detailTransaksi(): HasMany
    {
        return $this->hasMany(DetailTransaksi::class);
    }

    // Relasi Belongs-To ke User (Kasir)
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Urutkan riwayat terbaru dan muat data kasir beserta item transaksinya.
     */
    public function scopeRiwayatTerbaru(Builder $query): Builder
    {
        return $query
            ->with(['user:id,name', 'detailTransaksi.menu'])
            ->orderByDesc('tanggal')
            ->orderByDesc('id');
    }

    public function scopePadaTanggalLaporan(Builder $query, CarbonInterface $date): Builder
    {
        $startOfDay = $date->copy()->timezone(config('app.timezone'))->startOfDay();
        $endOfDay = $startOfDay->copy()->addDay();

        return $query
            ->where('tanggal', '>=', $startOfDay)
            ->where('tanggal', '<', $endOfDay);
    }

    public static function totalPenjualanHariIni(?CarbonInterface $today = null): int
    {
        $today ??= now(config('app.timezone'));

        return (int) static::query()
            ->padaTanggalLaporan($today)
            ->sum('total_harga');
    }
}
