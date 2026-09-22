<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;

class CartController extends Controller
{
    // Tampilkan isi keranjang
    public function index()
    {
        $cart = session()->get('cart', []);
        return view('kasir.cart', compact('cart'));
    }

    // Tambah item ke keranjang dengan Validasi Keamanan
    public function add(Request $request, $id)
    {
        // 1. Ambil data menu langsung dari DB Server (Keamanan Harga)
        $menu = Menu::findOrFail($id);

        // 2. Validasi: Tolak jika status menu 'habis'
        if ($menu->status_ketersediaan === 'habis') {
            return redirect()->back()->with('error', 'Menu ini sedang habis dan tidak dapat ditambahkan!');
        }

        // 3. Validasi: Jumlah minimal 1
        $jumlah = max(1, (int) $request->input('jumlah', 1));

        $cart = session()->get('cart', []);

        // 4. Jika item sudah ada di keranjang, tambahkan jumlahnya
        if (isset($cart[$id])) {
            $cart[$id]['jumlah'] += $jumlah;
            // Harga selalu dikali dengan harga asli dari DB server
            $cart[$id]['subtotal'] = $cart[$id]['jumlah'] * $menu->harga;
        } else {
            // Jika item belum ada, buat struktur item baru
            $cart[$id] = [
                "id_menu"   => $menu->id,
                "nama_menu" => $menu->nama_menu,
                "jumlah"    => $jumlah,
                "harga"     => $menu->harga, // Ambil dari server DB
                "subtotal"  => $jumlah * $menu->harga
            ];
        }

        session()->put('cart', $cart);
        return redirect()->back()->with('success', 'Menu berhasil ditambahkan ke keranjang!');
    }

    // Ubah jumlah item di keranjang dengan Validasi
    public function update(Request $request, $id)
    {
        // Validasi input request wajib integer dan minimal 1
        $request->validate([
            'jumlah' => 'required|integer|min:1'
        ], [
            'jumlah.integer' => 'Jumlah pesanan harus berupa angka.',
            'jumlah.min'     => 'Jumlah pesanan minimal 1.'
        ]);

        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            $menu = Menu::findOrFail($id);

            // Tolak update jika menu di-set 'habis' di tengah jalan oleh admin
            if ($menu->status_ketersediaan === 'habis') {
                unset($cart[$id]);
                session()->put('cart', $cart);
                return redirect()->back()->with('error', 'Menu telah diubah menjadi habis dan dihapus dari keranjang.');
            }

            $cart[$id]['jumlah'] = (int) $request->jumlah;
            $cart[$id]['subtotal'] = $cart[$id]['jumlah'] * $menu->harga;
            
            session()->put('cart', $cart);
            return redirect()->back()->with('success', 'Jumlah pesanan berhasil diperbarui!');
        }

        return redirect()->back()->with('error', 'Item tidak ditemukan di keranjang.');
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