<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    // Tampilkan isi keranjang
    public function index(): View
    {
        $cart = session()->get('cart', []);

        return view('kasir.cart', compact('cart'));
    }

    // Tambah item ke keranjang dengan Validasi Keamanan
    public function add(Request $request, int $id): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'jumlah' => ['nullable', 'integer', 'min:1'],
        ], [
            'jumlah.integer' => 'Jumlah pesanan harus berupa angka.',
            'jumlah.min' => 'Jumlah pesanan minimal 1.',
        ]);

        // 1. Ambil data menu langsung dari DB Server (Keamanan Harga)
        $menu = Menu::findOrFail($id);

        // 2. Validasi: Tolak jika status menu 'habis'
        if ($menu->status_ketersediaan === 'habis') {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Menu ini sedang habis dan tidak dapat ditambahkan.',
                ], 422);
            }

            return redirect()->back()->with('error', 'Menu ini sedang habis dan tidak dapat ditambahkan!');
        }

        // 3. Validasi: Jumlah minimal 1
        $jumlah = (int) ($validated['jumlah'] ?? 1);

        $cart = session()->get('cart', []);

        // 4. Jika item sudah ada di keranjang, tambahkan jumlahnya
        if (isset($cart[$id])) {
            $cart[$id]['jumlah'] += $jumlah;
            // Harga selalu dikali dengan harga asli dari DB server
            $cart[$id]['subtotal'] = $cart[$id]['jumlah'] * $menu->harga;
        } else {
            // Jika item belum ada, buat struktur item baru
            $cart[$id] = [
                'id_menu' => $menu->id,
                'nama_menu' => $menu->nama_menu,
                'jumlah' => $jumlah,
                'harga' => $menu->harga, // Ambil dari server DB
                'subtotal' => $jumlah * $menu->harga,
            ];
        }

        session()->put('cart', $cart);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Menu berhasil ditambahkan ke keranjang.',
                'item' => $cart[$id],
            ]);
        }

        return redirect()->back()->with('success', 'Menu berhasil ditambahkan ke keranjang!');
    }

    // Ubah jumlah item di keranjang dengan Validasi
    public function update(Request $request, int $id): JsonResponse|RedirectResponse
    {
        // Validasi input request wajib integer dan minimal 1
        $request->validate([
            'jumlah' => 'required|integer|min:1',
        ], [
            'jumlah.integer' => 'Jumlah pesanan harus berupa angka.',
            'jumlah.min' => 'Jumlah pesanan minimal 1.',
        ]);

        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            $menu = Menu::findOrFail($id);

            // Tolak update jika menu di-set 'habis' di tengah jalan oleh admin
            if ($menu->status_ketersediaan === 'habis') {
                unset($cart[$id]);
                session()->put('cart', $cart);

                if ($request->expectsJson()) {
                    return response()->json(['message' => 'Menu telah habis dan dihapus dari keranjang.'], 422);
                }

                return redirect()->back()->with('error', 'Menu telah diubah menjadi habis dan dihapus dari keranjang.');
            }

            $cart[$id]['jumlah'] = (int) $request->jumlah;
            $cart[$id]['subtotal'] = $cart[$id]['jumlah'] * $menu->harga;

            session()->put('cart', $cart);

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Jumlah pesanan berhasil diperbarui.',
                    'item' => $cart[$id],
                ]);
            }

            return redirect()->back()->with('success', 'Jumlah pesanan berhasil diperbarui!');
        }

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Item tidak ditemukan di keranjang.'], 404);
        }

        return redirect()->back()->with('error', 'Item tidak ditemukan di keranjang.');
    }

    // Hapus item dari keranjang
    public function remove(Request $request, int $id): JsonResponse|RedirectResponse
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Item berhasil dihapus dari keranjang.']);
        }

        return redirect()->back()->with('success', 'Item berhasil dihapus dari keranjang!');
    }

    public function clear(Request $request): JsonResponse|RedirectResponse
    {
        $request->session()->forget('cart');

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Keranjang berhasil dikosongkan.']);
        }

        return redirect()->back()->with('success', 'Keranjang berhasil dikosongkan!');
    }
}
