<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ShopController;
use App\Http\Middleware\EnsureAdmin;
use Illuminate\Support\Facades\Route;

// ---------- Toko (publik, tanpa login) ----------
Route::get('/', [ShopController::class, 'index'])->name('shop.index');

Route::prefix('keranjang')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::post('/tambah', [CartController::class, 'add'])->name('add');
    Route::delete('/', [CartController::class, 'clear'])->name('clear');
    Route::patch('/{product}', [CartController::class, 'update'])->name('update');
    Route::delete('/{product}', [CartController::class, 'remove'])->name('remove');
});

Route::get('/checkout', [CheckoutController::class, 'show'])->name('checkout.show');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/checkout/berhasil', [CheckoutController::class, 'success'])->name('checkout.success');

// Laravel mencari route bernama "login" saat pengunjung belum masuk; arahkan ke login admin.
Route::redirect('/login', '/admin/login')->name('login');

// ---------- Admin ----------
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AuthController::class, 'create'])->name('login');
    Route::post('/login', [AuthController::class, 'store'])->middleware('throttle:6,1')->name('login.store');

    Route::middleware(EnsureAdmin::class)->group(function () {
        Route::post('/logout', [AuthController::class, 'destroy'])->name('logout');
        Route::get('/', DashboardController::class)->name('dashboard');

        // Riwayat transaksi (per pesanan)
        Route::get('/transaksi', [OrderController::class, 'index'])->name('orders.index');
        Route::get('/transaksi/{order}', [OrderController::class, 'show'])->name('orders.show');
        Route::patch('/transaksi/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.status');

        // Tabel transaksi (per produk) + export
        Route::get('/tabel-transaksi', [TransactionController::class, 'index'])->name('transactions.index');
        Route::get('/tabel-transaksi/excel', [TransactionController::class, 'excel'])->name('transactions.excel');
        Route::get('/tabel-transaksi/pdf', [TransactionController::class, 'pdf'])->name('transactions.pdf');
    });
});
