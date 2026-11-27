<?php

namespace App\Http\Controllers\dapur;

use App\Http\Controllers\Controller;
use App\Models\ProductionSchedule;

class DashboardController extends Controller
{
    //index -> tampilin dashboard kepala dapur
    public function index()
    {
        $antrean = ProductionSchedule::where('status_produksi', 'antrean_dapur')->count();
        $dimasak = ProductionSchedule::where('status_produksi', 'sedang_dimasak')->count();
        $siapKirim = ProductionSchedule::where('status_produksi', 'siap_kirim')->count();
        $terkirim = ProductionSchedule::where('status_produksi', 'terkirim')->count();

        // sortBy = urutin hasilnya, di dalam function kita tentuin urutnya berdasarkan apa
        // di sini diurutin dari tanggal acara yang paling dekat (tanggal acara ada di tabel orders, makanya lewat $item->order)
        // take(5) = ambil 5 teratas aja
        $kerjaan = ProductionSchedule::with('order')
            ->where('status_produksi', '!=', 'terkirim')
            ->get()
            ->sortBy(function ($item) {
                return $item->order->tanggal_acara;
            })
            ->take(5);

        return view('dapur.pages.dashboard', compact('antrean', 'dimasak', 'siapKirim', 'terkirim', 'kerjaan'));
    }
}
