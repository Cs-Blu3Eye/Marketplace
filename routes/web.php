<?php
// routes/web.php

use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\MarketplaceController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisteredUserController; // Untuk modifikasi register

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Di sini Anda dapat mendaftarkan rute web untuk aplikasi Anda. Rute-rute ini
| dimuat oleh RouteServiceProvider dan semuanya akan diberi grup middleware "web".
| Buat sesuatu yang hebat!
|
*/

// Rute Halaman Utama
Route::get('/', [MarketplaceController::class, 'index'])->name('marketplace.index');
Route::get('/products/{product}', [MarketplaceController::class, 'show'])->name('marketplace.show');

// Rute Keranjang Belanja
Route::middleware('auth')->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
    Route::put('/cart/update/{product}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/remove/{product}', [CartController::class, 'remove'])->name('cart.remove');
});


// Rute Checkout (membutuhkan autentikasi)
Route::middleware(['auth'])->group(function () {
    Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');
    Route::get('/checkout/finish', [CheckoutController::class, 'finish'])->name('checkout.finish');
    Route::get('/checkout/pending', [CheckoutController::class, 'pending'])->name('checkout.pending');
    Route::get('/checkout/error', [CheckoutController::class, 'error'])->name('checkout.error');
    Route::get('/transactions', [CheckoutController::class, 'history'])->name('transactions.history');
    Route::get('/transactions/history/', [CheckoutController::class, 'finish'])->name('transactions.show');

});

// Rute Callback Midtrans (tidak memerlukan autentikasi)
Route::post('/midtrans/callback', [CheckoutController::class, 'callback'])->name('midtrans.callback');


// Rute Dashboard (setelah login)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Rute Profil (dari Breeze)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Rute Admin (dilindungi oleh middleware 'admin')
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // Resource routes untuk Product
    Route::resource('products', AdminProductController::class);
});

// Rute Autentikasi (dari Breeze)
require __DIR__.'/auth.php';

// Override rute register Breeze untuk menambahkan role default
Route::post('/register', [RegisteredUserController::class, 'store'])
    ->middleware('guest');
