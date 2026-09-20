<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;

class CartController extends Controller
{
    // Tampilkan isi keranjang belanja
    public function index()
    {
        $cart = session()->get('cart', []);
        return view('kasir.cart', compact('cart'));
    }

    // Tambah item ke keranjang
    public function add(Request $request, $id)
    {
        $menu = Menu::findOrFail($id);
        $cart = session()->get('cart', []);

        // Jika item sudah ada di keranjang, tambahkan jumlahnya
        if (isset($cart[$id])) {
            $cart[$id]['jumlah']++;
            $cart[$id]['subtotal'] = $cart[$id]['jumlah'] * $cart[$id]['harga'];
        } else {
            // Jika item belum ada, masukkan item baru
            $cart[$id] = [
                "id_menu" => $menu->id,
                "nama_menu" => $menu->nama_menu,
                "jumlah" => 1,
                "harga" => $menu->harga,
                "subtotal" => $menu->harga
            ];
        }

        session()->put('cart', $cart);
        return redirect()->back()->with('success', 'Menu berhasil ditambahkan ke keranjang!');
    }

    // Ubah jumlah/kuantitas item di keranjang
    public function update(Request $request, $id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id]) && $request->jumlah > 0) {
            $cart[$id]['jumlah'] = $request->jumlah;
            $cart[$id]['subtotal'] = $cart[$id]['jumlah'] * $cart[$id]['harga'];
            session()->put('cart', $cart);
            return redirect()->back()->with('success', 'Jumlah pesanan berhasil diperbarui!');
        }

        return redirect()->back()->with('error', 'Item tidak ditemukan atau jumlah tidak valid.');
    }

    // Hapus item dari keranjang
    public function remove($id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }

        return redirect()->back()->with('success', 'Item berhasil dihapus dari keranjang!');
    }
}