<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    // Mengizinkan kolom diisi secara massal
    protected $fillable = ['user_id', 'tanggal', 'total_harga'];

    // Relasi One-to-Many ke DetailTransaksi
    public function detailTransaksi()
    {
        return $this->hasMany(DetailTransaksi::class);
    }

    // Relasi Belongs-To ke User (Kasir)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}