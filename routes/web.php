<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicMenuController;
use App\Http\Controllers\TransaksiController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PublicMenuController::class, 'index'])->name('public.menu');

Route::get('/dashboard', DashboardController::class)->middleware('auth')->name('dashboard');

// Tautan lama menu publik tetap berfungsi.
Route::redirect('/menu', '/');

// Rute yang Membutuhkan Login (Auth)
Route::middleware('auth')->group(function () {
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile/avatar', [ProfileController::class, 'destroyAvatar'])->name('profile.avatar.destroy');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Kasir / Keranjang
    Route::get('/kasir/keranjang', [CartController::class, 'index'])->name('cart.index');
    Route::post('/kasir/keranjang/tambah/{id}', [CartController::class, 'add'])->name('cart.add');
    Route::post('/kasir/keranjang/update/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/kasir/keranjang/hapus/{id}', [CartController::class, 'remove'])->name('cart.remove');
    Route::delete('/kasir/keranjang', [CartController::class, 'clear'])->name('cart.clear');
    Route::post('/kasir/transaksi', [TransaksiController::class, 'store'])->name('transactions.store');

    Route::get('/riwayat', [TransaksiController::class, 'index'])->name('transactions.index');
    Route::get('/riwayat/{transaksi}', [TransaksiController::class, 'show'])->name('transactions.show');

    Route::get('/kelola-menu', [MenuController::class, 'index'])->name('menu.index');
    Route::post('/kelola-menu', [MenuController::class, 'store'])->name('menu.store');
    Route::patch('/kelola-menu/{menu}', [MenuController::class, 'update'])->name('menu.update');
    Route::delete('/kelola-menu/{menu}', [MenuController::class, 'destroy'])->name('menu.destroy');
    Route::post('/kelola-menu/{menu}/restore', [MenuController::class, 'restore'])->name('menu.restore');
});

require __DIR__.'/auth.php';
