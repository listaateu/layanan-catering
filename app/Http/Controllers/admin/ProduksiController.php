<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\ProductionSchedule;
use Illuminate\Http\Request;

class ProduksiController extends Controller
{
    // Admin hanya memantau antrean dapur (baca saja). Yang mengubah status adalah kepala dapur.
    public function index(Request $request)
    {
        $status = $request->query('status');

        $produksi = ProductionSchedule::with('order.customer', 'order.items.package', 'handler')
            ->when($status, fn ($query) => $query->where('status_produksi', $status))
            ->get()
            ->sortBy(fn ($item) => $item->order->tanggal_acara->format('Y-m-d') . ' ' . $item->order->jam_kirim)
            ->values();

        return view('admin.pages.produksi.produksi', compact('produksi', 'status'));
    }
}
