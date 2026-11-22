<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class PesananController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');

        $pesanan = Order::with('customer')
            ->when($status, fn ($query) => $query->where('status_pesanan', $status))
            ->latest()
            ->get();

        return view('admin.pages.pesanan.pesanan', compact('pesanan', 'status'));
    }

    public function show(Order $pesanan)
    {
        $pesanan->load('customer', 'items.package', 'payment', 'productionSchedule');
        return view('admin.pages.pesanan.show', compact('pesanan'));
    }

    public function batal(Order $pesanan)
    {
        if (!in_array($pesanan->status_pesanan, ['menunggu_pembayaran', 'menunggu_verifikasi', 'terkonfirmasi'])) {
            return redirect()->route('admin.pesanan.show', $pesanan->id_order)
                ->with('error', 'Pesanan dengan status ini tidak bisa dibatalkan');
        }

        // kalau sudah sempat masuk antrean dapur, jadwalnya dihapus
        $pesanan->productionSchedule?->delete();
        $pesanan->update(['status_pesanan' => 'dibatalkan']);

        return redirect()->route('admin.pesanan')->with('success', 'Pesanan ' . $pesanan->kode_order . ' berhasil dibatalkan');
    }
}
