<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

use App\Http\Controllers\CartController;

Route::middleware(['auth'])->group(function () {
    Route::get('/kasir/keranjang', [CartController::class, 'index'])->name('cart.index');
    Route::post('/kasir/keranjang/tambah/{id}', [CartController::class, 'add'])->name('cart.add');
    Route::post('/kasir/keranjang/update/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/kasir/keranjang/hapus/{id}', [CartController::class, 'remove'])->name('cart.remove');
});
use App\Http\Controllers\PublicMenuController;

Route::get('/menu', [PublicMenuController::class, 'index'])->name('public.menu');
