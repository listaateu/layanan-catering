<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class PesananController extends Controller
{
    //index -> tampilin daftar pesanan, bisa difilter status dan dicari berdasarkan kode order
    public function index(Request $request)
    {
        // pesanan yang lewat batas bayar dibatalkan dulu, biar statusnya sudah terbaru
        Order::batalkanKadaluarsa();

        $status = $request->status;
        $cari = $request->cari;

        $query = Order::latest();

        if ($status) {
            $query->where('status_pesanan', $status);
        }

        // pencarian: kode order mengandung kata yang diketik (contoh: 001 atau ORD-20261127)
        if ($cari) {
            $query->where('kode_order', 'like', '%' . $cari . '%');
        }

        $pesanan = $query->get();

        return view('admin.pages.pesanan.pesanan', compact('pesanan', 'status', 'cari'));
    }

    //show -> tampilin detail satu pesanan
    public function show($id)
    {
        $pesanan = Order::find($id);

        return view('admin.pages.pesanan.show', compact('pesanan'));
    }

    //batal -> batalin pesanan
    public function batal(Order $pesanan)
    {
        // pesanan yang sudah dimasak atau selesai ga bisa dibatalin
        // kalau sudah dibayar, data pembayarannya tetap disimpan sebagai catatan, uangnya dikembalikan kasir langsung
        if (!in_array($pesanan->status_pesanan, ['menunggu_pembayaran', 'terkonfirmasi'])) {
            return redirect()->route('admin.pesanan.show', $pesanan->id_order)
                ->with('error', 'Pesanan dengan status ini tidak bisa dibatalkan');
        }

        // kalau sudah masuk antrean dapur, jadwalnya dihapus
        if ($pesanan->productionSchedule) {
            $pesanan->productionSchedule->delete();
        }

        $pesanan->update([
            'status_pesanan' => 'dibatalkan',
        ]);

        return redirect()->route('admin.pesanan.table')->with('success', 'Pesanan ' . $pesanan->kode_order . ' berhasil dibatalkan' . ($pesanan->payment ? '. Jangan lupa kembalikan uang pelanggan Rp ' . number_format($pesanan->payment->jumlah_bayar, 0, ',', '.') : ''));
    }
}
