<?php

use App\Http\Controllers\AuthController as LoginController;
use App\Http\Controllers\admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\admin\PaketController as AdminPaketController;
use App\Http\Controllers\admin\PesananController as AdminPesananController;
use App\Http\Controllers\admin\PembayaranController as AdminPembayaranController;
use App\Http\Controllers\admin\ProduksiController as AdminProduksiController;
use App\Http\Controllers\dapur\DashboardController as DapurDashboardController;
use App\Http\Controllers\dapur\ProduksiController as DapurProduksiController;
use App\Http\Controllers\dapur\RiwayatController as DapurRiwayatController;
use App\Http\Controllers\pelanggan\BerandaController as PelangganBerandaController;
use App\Http\Controllers\pelanggan\PesananController as PelangganPesananController;
use App\Http\Middleware\CekAdminKasir;
use App\Http\Middleware\CekKepalaDapur;
use App\Http\Middleware\CekPelanggan;
use Illuminate\Support\Facades\Route;

// Halaman Pelanggan (tanpa login)
Route::as('pelanggan.')->group(function () {
    Route::get('/', [PelangganBerandaController::class, 'index'])->name('beranda');
    Route::get('/menu', [PelangganBerandaController::class, 'menu'])->name('menu');
    Route::get('/menu/{id}', [PelangganBerandaController::class, 'show'])->name('menu.show');
    Route::get('/tentang', [PelangganBerandaController::class, 'tentang'])->name('tentang');
    Route::get('/kontak', [PelangganBerandaController::class, 'kontak'])->name('kontak');
});

// Login Page
Route::prefix('login')->group(function () {
    Route::get('/', [LoginController::class, 'index'])->name('login');
    Route::post('/', [LoginController::class, 'authenticate'])->name('authenticate');
});

Route::get('/register', [LoginController::class, 'register'])->name('register');
Route::post('/register', [LoginController::class, 'registerStore'])->name('register.store');

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Admin Kasir
Route::prefix('admin-kasir')->as('admin.')->middleware([CekAdminKasir::class])->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::prefix('paket')->as('paket.')->group(function () {
        Route::get('/', [AdminPaketController::class, 'index'])->name('table');
        Route::get('/create', [AdminPaketController::class, 'create'])->name('create');
        Route::post('/create', [AdminPaketController::class, 'store'])->name('store');
        Route::get('/update/{id}', [AdminPaketController::class, 'edit'])->name('edit');
        Route::put('/update/{paket}', [AdminPaketController::class, 'update'])->name('update');
        Route::delete('/delete/{paket}', [AdminPaketController::class, 'destroy'])->name('destroy');
    });
    Route::prefix('pesanan')->as('pesanan.')->group(function () {
        Route::get('/', [AdminPesananController::class, 'index'])->name('table');
        Route::get('/show/{id}', [AdminPesananController::class, 'show'])->name('show');
        Route::put('/batal/{pesanan}', [AdminPesananController::class, 'batal'])->name('batal');
    });
    Route::prefix('pembayaran')->as('pembayaran.')->group(function () {
        Route::get('/', [AdminPembayaranController::class, 'index'])->name('table');
        Route::get('/create/{id}', [AdminPembayaranController::class, 'create'])->name('create');
        Route::post('/create/{pesanan}', [AdminPembayaranController::class, 'store'])->name('store');
        Route::get('/show/{id}', [AdminPembayaranController::class, 'show'])->name('show');
    });
    Route::prefix('produksi')->as('produksi.')->group(function () {
        Route::get('/', [AdminProduksiController::class, 'index'])->name('table');
    });
});

// Kepala Dapur
Route::prefix('kepala-dapur')->as('dapur.')->middleware([CekKepalaDapur::class])->group(function () {
    Route::get('/', [DapurDashboardController::class, 'index'])->name('dashboard');
    Route::prefix('produksi')->as('produksi.')->group(function () {
        Route::get('/', [DapurProduksiController::class, 'index'])->name('table');
        Route::get('/update/{id}', [DapurProduksiController::class, 'edit'])->name('edit');
        Route::put('/update/{produksi}', [DapurProduksiController::class, 'update'])->name('update');
    });
    Route::prefix('riwayat')->as('riwayat.')->group(function () {
        Route::get('/', [DapurRiwayatController::class, 'index'])->name('table');
        Route::get('/show/{id}', [DapurRiwayatController::class, 'show'])->name('show');
    });
});

// Pelanggan (harus login)
Route::prefix('pelanggan')->as('pelanggan.')->middleware([CekPelanggan::class])->group(function () {
    Route::prefix('pesanan')->as('pesanan.')->group(function () {
        Route::get('/', [PelangganPesananController::class, 'index'])->name('table');
        Route::get('/create/{id}', [PelangganPesananController::class, 'create'])->name('create');
        Route::post('/create', [PelangganPesananController::class, 'store'])->name('store');
        Route::get('/show/{id}', [PelangganPesananController::class, 'show'])->name('show');
        Route::put('/batal/{pesanan}', [PelangganPesananController::class, 'batal'])->name('batal');
    });
});
