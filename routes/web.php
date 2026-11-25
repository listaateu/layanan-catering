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
use App\Http\Middleware\CekAdminKasir;
use App\Http\Middleware\CekKepalaDapur;
use Illuminate\Support\Facades\Route;

// Halaman utama (/) langsung diarahkan ke halaman login
Route::get('/', function () {
    return redirect()->route('login');
});

// Login Page
// prefix('login') = semua alamat di dalam group diawali /login
// get = menampilkan halaman, post = memproses data yang dikirim dari form
// name() = nama route, dipakai di view dengan route('nama')
Route::prefix('login')->group(function () {
    Route::get('/', [LoginController::class, 'index'])->name('login');
    Route::post('/', [LoginController::class, 'authenticate'])->name('authenticate');
});

// logout memakai POST (dikirim lewat form + @csrf) supaya lebih aman
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Admin Kasir
// prefix('admin-kasir') = alamat diawali /admin-kasir
// as('admin.') = nama route diawali admin.  (contoh: admin.paket.table)
// middleware = satpam, hanya admin_kasir yang boleh masuk
Route::prefix('admin-kasir')->as('admin.')->middleware([CekAdminKasir::class])->group(function () {
    // {id} = bagian alamat yang berubah-ubah (nomor data). {paket} = Laravel otomatis mencari datanya (route model binding)
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
    // Paket menu: index(table), create, store, edit, update, destroy
    Route::prefix('paket')->as('paket.')->group(function () {
        Route::get('/', [AdminPaketController::class, 'index'])->name('table');
        Route::get('/create', [AdminPaketController::class, 'create'])->name('create');
        Route::post('/create', [AdminPaketController::class, 'store'])->name('store');
        Route::get('/update/{id}', [AdminPaketController::class, 'edit'])->name('edit');
        Route::put('/update/{paket}', [AdminPaketController::class, 'update'])->name('update');
        Route::delete('/delete/{paket}', [AdminPaketController::class, 'destroy'])->name('destroy');
    });
    // Pesanan: daftar, detail (show), dan pembatalan (batal)
    Route::prefix('pesanan')->as('pesanan.')->group(function () {
        Route::get('/', [AdminPesananController::class, 'index'])->name('table');
        Route::get('/show/{id}', [AdminPesananController::class, 'show'])->name('show');
        Route::put('/batal/{pesanan}', [AdminPesananController::class, 'batal'])->name('batal');
    });
    // Pembayaran: daftar, halaman validasi (edit), simpan hasil validasi (update), detail (show)
    Route::prefix('pembayaran')->as('pembayaran.')->group(function () {
        Route::get('/', [AdminPembayaranController::class, 'index'])->name('table');
        Route::get('/update/{id}', [AdminPembayaranController::class, 'edit'])->name('validasi');
        Route::put('/update/{pembayaran}', [AdminPembayaranController::class, 'update'])->name('update');
        Route::get('/show/{id}', [AdminPembayaranController::class, 'show'])->name('show');
    });
    // Produksi: admin hanya melihat (tidak ada tambah/ubah/hapus)
    Route::prefix('produksi')->as('produksi.')->group(function () {
        Route::get('/', [AdminProduksiController::class, 'index'])->name('table');
    });
});

// Kepala Dapur
// prefix('kepala-dapur') = alamat diawali /kepala-dapur
// as('dapur.') = nama route diawali dapur.  (contoh: dapur.produksi.table)
// middleware = satpam, hanya kepala_dapur yang boleh masuk
Route::prefix('kepala-dapur')->as('dapur.')->middleware([CekKepalaDapur::class])->group(function () {
    Route::get('/', [DapurDashboardController::class, 'index'])->name('dashboard');
    // Produksi: daftar antrean, halaman ubah status (edit), simpan perubahan status (update)
    Route::prefix('produksi')->as('produksi.')->group(function () {
        Route::get('/', [DapurProduksiController::class, 'index'])->name('table');
        Route::get('/update/{id}', [DapurProduksiController::class, 'edit'])->name('edit');
        Route::put('/update/{produksi}', [DapurProduksiController::class, 'update'])->name('update');
    });
    // Riwayat: pesanan yang sudah terkirim (hanya lihat)
    Route::prefix('riwayat')->as('riwayat.')->group(function () {
        Route::get('/', [DapurRiwayatController::class, 'index'])->name('table');
        Route::get('/show/{id}', [DapurRiwayatController::class, 'show'])->name('show');
    });
});
