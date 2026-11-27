<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\ProductionSchedule;
use Illuminate\Http\Request;

class ProduksiController extends Controller
{
    //index -> tampilin antrean dapur, admin cuma bisa lihat (yang ubah status kepala dapur)
    public function index(Request $request)
    {
        $status = $request->status;

        if ($status) {
            $produksi = ProductionSchedule::where('status_produksi', $status)->get();
        } else {
            $produksi = ProductionSchedule::get();
        }

        // sortBy = urutin hasilnya, sama seperti di dashboard dapur
        // diurutkan dari tanggal acara paling dekat, kalau tanggalnya sama dari jam kirim paling pagi
        $produksi = $produksi->sortBy(function ($item) {
            return $item->order->tanggal_acara->format('Y-m-d') . ' ' . $item->order->jam_kirim;
        });

        return view('admin.pages.produksi.produksi', compact('produksi', 'status'));
    }
}
