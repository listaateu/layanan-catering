<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\CateringPackage;
use App\Models\Order;
use App\Models\Payment;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $totalPaket = CateringPackage::count();
        $totalPesanan = Order::count();
        $pembayaranMenunggu = Payment::where('status_verifikasi', 'menunggu')->count();

        // pendapatan = pesanan yang pembayarannya sudah diterima dan tidak dibatalkan
        $totalPendapatan = Order::whereIn('status_pesanan', ['terkonfirmasi', 'diproses', 'selesai'])->sum('total_bayar');

        $pesananBaru = Order::with('customer')->latest()->take(5)->get();

        // acara 7 hari ke depan yang sudah dibayar dan belum selesai
        $acaraMendatang = Order::with('customer', 'productionSchedule')
            ->whereIn('status_pesanan', ['terkonfirmasi', 'diproses'])
            ->whereBetween('tanggal_acara', [today(), today()->addDays(7)])
            ->orderBy('tanggal_acara')
            ->orderBy('jam_kirim')
            ->get();

        return view('admin.pages.dashboard', compact(
            'user',
            'totalPaket',
            'totalPesanan',
            'pembayaranMenunggu',
            'totalPendapatan',
            'pesananBaru',
            'acaraMendatang'
        ));
    }
}
