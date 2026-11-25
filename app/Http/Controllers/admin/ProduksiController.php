<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\ProductionSchedule;
use Illuminate\Http\Request;

class ProduksiController extends Controller
{
    // index -> menampilkan halaman utama (antrean dapur)
    // admin hanya memantau (baca saja), yang mengubah status adalah kepala dapur
    // makanya di controller ini hanya ada function index
    public function index(Request $request)
    {
        // ambil status dari alamat, contoh: ?status=sedang_dimasak
        $status = $request->status;

        // kalau ada status yang dipilih, tampilkan yang statusnya sama saja
        // kalau tidak ada, tampilkan semua jadwal produksi
        if ($status) {
            $produksi = ProductionSchedule::where('status_produksi', $status)->get();
        } else {
            $produksi = ProductionSchedule::get();
        }

        return view('admin.pages.produksi.produksi', compact('produksi', 'status'));
    }
}
