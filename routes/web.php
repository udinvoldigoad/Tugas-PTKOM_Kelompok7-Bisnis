<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicMenuController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard', ['user' => request()->user()]);
})->middleware(['auth', 'verified'])->name('dashboard');

// Menu Publik
Route::get('/menu', [PublicMenuController::class, 'index'])->name('public.menu');

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

    // Kelola Menu Kasir (Punya Niken)
    Route::get('/kelola-menu', function () {
        return view('menu.index');
    });
});

require __DIR__.'/auth.php';