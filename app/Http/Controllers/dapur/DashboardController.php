<?php

namespace App\Http\Controllers\dapur;

use App\Http\Controllers\Controller;
use App\Models\ProductionSchedule;

class DashboardController extends Controller
{
    // index -> menampilkan halaman dashboard kepala dapur (ringkasan kerjaan dapur)
    // dashboard hanya menampilkan data, tidak ada form, jadi cukup 1 function ini
    public function index()
    {
        // count() = menghitung jumlah baris data
        // where() = menyaring data, di sini per status produksi
        $antrean = ProductionSchedule::where('status_produksi', 'antrean_dapur')->count();
        $dimasak = ProductionSchedule::where('status_produksi', 'sedang_dimasak')->count();
        $siapKirim = ProductionSchedule::where('status_produksi', 'siap_kirim')->count();
        $terkirim = ProductionSchedule::where('status_produksi', 'terkirim')->count();

        // pesanan yang masih harus dikerjakan (belum terkirim)
        // where('status_produksi', '!=', 'terkirim') = statusnya bukan terkirim
        // with('order') = ambil data pesanannya sekaligus supaya lebih ringan
        // take(5) = ambil 5 saja
        $kerjaan = ProductionSchedule::with('order')
            ->where('status_produksi', '!=', 'terkirim')
            ->get()
            ->sortBy(function ($item) {
                // urutkan dari tanggal acara yang paling dekat
                return $item->order->tanggal_acara;
            })
            ->take(5);

        // compact() = mengirim semua variabel ini ke halaman (view) supaya bisa dipakai di sana
        return view('dapur.pages.dashboard', compact('antrean', 'dimasak', 'siapKirim', 'terkirim', 'kerjaan'));
    }
}
