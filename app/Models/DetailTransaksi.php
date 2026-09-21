<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailTransaksi extends Model
{
    // Memastikan subtotal (harga saat transaksi) dan menu_id bisa disimpan
    protected $fillable = ['transaksi_id', 'menu_id', 'jumlah', 'subtotal'];

    // Relasi Belongs-To ke Transaksi
    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class);
    }

    // Relasi Belongs-To ke Menu
    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }
}