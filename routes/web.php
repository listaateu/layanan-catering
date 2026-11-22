<?php

use App\Http\Controllers\AuthController as LoginController;
use App\Http\Controllers\admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\admin\PaketController as AdminPaketController;
use App\Http\Controllers\admin\PesananController as AdminPesananController;
use App\Http\Controllers\admin\PembayaranController as AdminPembayaranController;
use App\Http\Middleware\CekAdminKasir;
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
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Paket Menu (CRUD)
    Route::resource('paket', AdminPaketController::class)->except('show');

    // Pesanan
    Route::prefix('pesanan')->group(function () {
        Route::get('/', [AdminPesananController::class, 'index'])->name('pesanan');
        Route::get('/show/{pesanan}', [AdminPesananController::class, 'show'])->name('pesanan.show');
        Route::put('/batal/{pesanan}', [AdminPesananController::class, 'batal'])->name('pesanan.batal');
    });

    // Verifikasi Pembayaran
    Route::prefix('pembayaran')->group(function () {
        Route::get('/', [AdminPembayaranController::class, 'index'])->name('pembayaran');
        Route::get('/validasi/{pembayaran}', [AdminPembayaranController::class, 'edit'])->name('pembayaran.validasi');
        Route::put('/validasi/{pembayaran}', [AdminPembayaranController::class, 'update'])->name('pembayaran.update');
    });
});
