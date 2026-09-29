<?php

namespace App\Http\Controllers;

use App\Http\Requests\MenuRequest;
use App\Models\Menu;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class MenuController extends Controller
{
    public function index(): View
    {
        return view('menu.index', [
            'menus' => Menu::query()->orderBy('nama_menu')->get(),
        ]);
    }

    public function store(MenuRequest $request): JsonResponse|RedirectResponse
    {
        $data = $request->safe()->except(['foto', 'hapus_foto']);

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('menus', 'public');
        }

        $menu = Menu::create($data);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Menu berhasil ditambahkan.',
                'menu' => $menu,
            ], 201);
        }

        return redirect()->route('menu.index')->with('success', 'Menu berhasil ditambahkan.');
    }

    public function update(MenuRequest $request, Menu $menu): JsonResponse|RedirectResponse
    {
        $data = $request->safe()->except(['foto', 'hapus_foto']);
        $oldPhoto = $menu->foto;

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('menus', 'public');
        } elseif ($request->boolean('hapus_foto')) {
            $data['foto'] = null;
        }

        $menu->update($data);

        if ($oldPhoto && array_key_exists('foto', $data) && $oldPhoto !== $data['foto']) {
            Storage::disk('public')->delete($oldPhoto);
        }

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Menu berhasil diperbarui.',
                'menu' => $menu->fresh(),
            ]);
        }

        return redirect()->route('menu.index')->with('success', 'Menu berhasil diperbarui.');
    }

    public function destroy(Request $request, Menu $menu): JsonResponse|RedirectResponse
    {
        $menu->delete();

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Menu berhasil dihapus.']);
        }

        return redirect()->route('menu.index')->with('success', 'Menu berhasil dihapus.');
    }

    public function restore(Request $request, int $menu): JsonResponse|RedirectResponse
    {
        $restoredMenu = Menu::onlyTrashed()->findOrFail($menu);
        $restoredMenu->restore();

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Penghapusan menu berhasil dibatalkan.',
                'menu' => $restoredMenu->fresh(),
            ]);
        }

        return redirect()->route('menu.index')->with('success', 'Penghapusan menu berhasil dibatalkan.');
    }
}
