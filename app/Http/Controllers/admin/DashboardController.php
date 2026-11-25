<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\CateringPackage;
use App\Models\Order;
use App\Models\Payment;

class DashboardController extends Controller
{
    // index -> menampilkan halaman dashboard (ringkasan data)
    // dashboard hanya menampilkan data, tidak ada form, jadi cukup 1 function ini
    public function index()
    {
        // ambil data user yang sedang login
        $user = auth()->user();

        // count() = menghitung jumlah baris data di tabel
        $totalPaket = CateringPackage::count();
        $totalPesanan = Order::count();

        // where() = menyaring data, di sini hanya pembayaran yang statusnya masih 'menunggu'
        $pembayaranMenunggu = Payment::where('status_verifikasi', 'menunggu')->count();

        // pendapatan = jumlah kolom total_bayar dari pesanan yang pembayarannya sudah diterima
        // whereIn() = statusnya boleh salah satu dari daftar di dalam [ ]
        // sum() = menjumlahkan semua nilai di kolom itu
        $totalPendapatan = Order::whereIn('status_pesanan', ['terkonfirmasi', 'diproses', 'selesai'])->sum('total_bayar');

        // 5 pesanan terbaru
        // latest() = urutkan dari yang paling baru, take(5) = ambil 5 saja
        $pesananBaru = Order::latest()->take(5)->get();

        // acara 7 hari ke depan yang sudah dibayar dan belum selesai
        // date('Y-m-d') = tanggal hari ini, strtotime('+7 days') = 7 hari dari sekarang
        $hariIni = date('Y-m-d');
        $tujuhHariLagi = date('Y-m-d', strtotime('+7 days'));

        // whereBetween() = tanggal acara harus ada di antara hari ini dan 7 hari lagi
        // orderBy() = urutkan dari tanggal acara yang paling dekat
        $acaraMendatang = Order::whereIn('status_pesanan', ['terkonfirmasi', 'diproses'])
            ->whereBetween('tanggal_acara', [$hariIni, $tujuhHariLagi])
            ->orderBy('tanggal_acara')
            ->get();

        // compact() = mengirim semua variabel ini ke halaman (view) supaya bisa dipakai di sana
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
