<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\CateringPackage;
use App\Models\Order;

class DashboardController extends Controller
{
    //index -> tampilin dashboard (ringkasan data)
    public function index()
    {
        // pesanan yang lewat batas bayar dibatalkan dulu, biar angka di dashboard sudah terbaru
        Order::batalkanKadaluarsa();

        $user = auth()->user();

        $totalPaket = CateringPackage::count();
        $totalPesanan = Order::count();

        // pesanan yang belum dibayar tunai
        $belumBayar = Order::where('status_pesanan', 'menunggu_pembayaran')->count();

        // pendapatan = total pesanan yang sudah dibayar (bukan yang dibatalkan)
        $totalPendapatan = Order::whereIn('status_pesanan', ['terkonfirmasi', 'diproses', 'selesai'])->sum('total_bayar');

        // 5 pesanan terbaru
        $pesananBaru = Order::latest()->take(5)->get();

        // acara 7 hari ke depan yang sudah dibayar dan belum selesai
        $hariIni = date('Y-m-d');
        $tujuhHariLagi = date('Y-m-d', strtotime('+7 days'));

        $acaraMendatang = Order::whereIn('status_pesanan', ['terkonfirmasi', 'diproses'])
            ->whereBetween('tanggal_acara', [$hariIni, $tujuhHariLagi])
            ->orderBy('tanggal_acara')
            ->get();

        return view('admin.pages.dashboard', compact(
            'user',
            'totalPaket',
            'totalPesanan',
            'belumBayar',
            'totalPendapatan',
            'pesananBaru',
            'acaraMendatang'
        ));
    }
}
