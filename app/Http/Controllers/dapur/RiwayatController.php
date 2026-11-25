<?php

namespace App\Http\Controllers\dapur;

use App\Http\Controllers\Controller;
use App\Models\ProductionSchedule;

class RiwayatController extends Controller
{
    // index -> menampilkan halaman utama riwayat (pesanan yang sudah terkirim)
    public function index()
    {
        // where() = menyaring data, di sini hanya yang statusnya sudah terkirim
        // latest() = data yang paling baru ada di atas
        $riwayat = ProductionSchedule::where('status_produksi', 'terkirim')->latest()->get();

        return view('dapur.pages.riwayat.riwayat', compact('riwayat'));
    }

    // show -> menampilkan detail satu pesanan di riwayat
    public function show($id)
    {
        // find = cari 1 data jadwal produksi berdasarkan id
        $produksi = ProductionSchedule::find($id);

        return view('dapur.pages.riwayat.show', compact('produksi'));
    }
}
