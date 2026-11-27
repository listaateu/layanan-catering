<?php

namespace App\Http\Controllers\dapur;

use App\Http\Controllers\Controller;
use App\Models\ProductionSchedule;

class RiwayatController extends Controller
{
    //index -> tampilin pesanan yang sudah terkirim
    public function index()
    {
        $riwayat = ProductionSchedule::where('status_produksi', 'terkirim')->latest()->get();

        return view('dapur.pages.riwayat.riwayat', compact('riwayat'));
    }

    //show -> tampilin detail satu riwayat
    public function show($id)
    {
        $produksi = ProductionSchedule::find($id);

        return view('dapur.pages.riwayat.show', compact('produksi'));
    }
}
