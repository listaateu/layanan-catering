<?php

use App\Http\Controllers\AuthController as LoginController;
use App\Http\Controllers\admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\admin\PaketController as AdminPaketController;
use App\Http\Controllers\admin\PesananController as AdminPesananController;
use App\Http\Controllers\admin\PembayaranController as AdminPembayaranController;
use App\Http\Controllers\admin\ProduksiController as AdminProduksiController;
use App\Http\Controllers\dapur\DashboardController as DapurDashboardController;
use App\Http\Controllers\dapur\ProduksiController as DapurProduksiController;
use App\Http\Middleware\CekAdminKasir;
use App\Http\Middleware\CekKepalaDapur;
use App\Http\Middleware\CekPelanggan;
use Illuminate\Support\Facades\Route;

// Head Http
Route::get('/', function () {
    return redirect()->route('login');
});

// Login Page
Route::prefix('login')->group(function () {
    Route::get('/', [LoginController::class, 'index'])->name('login');
    Route::post('/', [LoginController::class, 'authenticate'])->name('authenticate');
});

Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

// Admin Kasir
Route::prefix('admin')->as('admin.')->middleware([CekAdminKasir::class])->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::prefix('paket')->group(function () {
        Route::get('/', [AdminPaketController::class, 'index'])->name('paket');
        Route::get('/create', [AdminPaketController::class, 'create'])->name('paket.create');
        Route::post('/create', [AdminPaketController::class, 'store'])->name('paket.store');
        Route::get('/update/{id}', [AdminPaketController::class, 'edit'])->name('paket.edit');
        Route::put('/update/{paket}', [AdminPaketController::class, 'update'])->name('paket.update');

        Route::delete('/delete/{paket}', [AdminPaketController::class, 'destroy'])->name('paket.destroy');
    });
    Route::prefix('pesanan')->group(function () {
        Route::get('/', [AdminPesananController::class, 'index'])->name('pesanan');
        Route::get('/show/{id}', [AdminPesananController::class, 'show'])->name('pesanan.show');
        Route::put('/batal/{pesanan}', [AdminPesananController::class, 'batal'])->name('pesanan.batal');
    });
    Route::prefix('pembayaran')->group(function () {
        Route::get('/', [AdminPembayaranController::class, 'index'])->name('pembayaran');
        Route::get('/update/{id}', [AdminPembayaranController::class, 'edit'])->name('pembayaran.validasi');
        Route::put('/update/{pembayaran}', [AdminPembayaranController::class, 'update'])->name('pembayaran.update');
        Route::get('/show/{id}', [AdminPembayaranController::class, 'show'])->name('pembayaran.show');
    });
    Route::prefix('produksi')->group(function () {
        Route::get('/', [AdminProduksiController::class, 'index'])->name('produksi');
    });
});

// Kepala Dapur
Route::prefix('dapur')->as('dapur.')->middleware([CekKepalaDapur::class])->group(function () {
    Route::get('/', [DapurDashboardController::class, 'index'])->name('dashboard');
    Route::prefix('produksi')->group(function () {
        Route::get('/', [DapurProduksiController::class, 'index'])->name('produksi');
        Route::get('/show/{id}', [DapurProduksiController::class, 'show'])->name('produksi.show');
        Route::put('/update/{produksi}', [DapurProduksiController::class, 'update'])->name('produksi.update');
    });
});

// Pelanggan
Route::prefix('pelanggan')->as('pelanggan.')->middleware([CekPelanggan::class])->group(function () {
    Route::get('/', function () {
        return 'Halaman pelanggan belum dibuat. <a href="' . route('logout') . '">Logout</a>';
    })->name('dashboard');
});
