<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $fillable = [
        'nama_menu',
        'kategori',
        'harga',
        'foto',
        'status_ketersediaan',
    ];
}
