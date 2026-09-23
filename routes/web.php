<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransaksiController;
use App\Models\Menu;
use App\Models\Transaksi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $user = Auth::user();
    $menus = Menu::all();
    $transaksis = Transaksi::with(['details.menu', 'user'])
        ->where('user_id', $user->id)
        ->latest()
        ->take(15)
        ->get();

    return view('dashboard', compact('user', 'menus', 'transaksis'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::post('/transaksi', [TransaksiController::class, 'store'])
    ->middleware('auth')
    ->name('transaksi.store');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::delete('/profile/avatar', [ProfileController::class, 'destroyAvatar'])->name('profile.avatar.destroy');
});

require __DIR__.'/auth.php';
