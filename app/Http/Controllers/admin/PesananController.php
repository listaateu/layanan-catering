<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PesananController extends Controller
{
    // index -> menampilkan halaman utama (daftar pesanan, bisa difilter status)
    public function index(Request $request)
    {
        // ambil status dari alamat, contoh: ?status=selesai
        $status = $request->status;

        // kalau ada status yang dipilih, tampilkan yang statusnya sama saja
        // kalau tidak ada, tampilkan semua pesanan
        if ($status) {
            $pesanan = Order::where('status_pesanan', $status)->latest()->get();
        } else {
            $pesanan = Order::latest()->get();
        }

        return view('admin.pages.pesanan.pesanan', compact('pesanan', 'status'));
    }

    // show -> menampilkan detail satu pesanan
    public function show($id)
    {
        // find = cari 1 data pesanan berdasarkan id
        $pesanan = Order::find($id);
        return view('admin.pages.pesanan.show', compact('pesanan'));
    }

    // batal -> membatalkan pesanan
    // Order $pesanan = Laravel otomatis mencari pesanan sesuai id di alamat URL
    public function batal(Order $pesanan)
    {
        // hanya pesanan dengan status tertentu yang boleh dibatalkan
        // in_array() = cek apakah status pesanan ada di dalam daftar
        if (!in_array($pesanan->status_pesanan, ['menunggu_pembayaran', 'menunggu_verifikasi', 'terkonfirmasi'])) {
            return redirect()->route('admin.pesanan.show', $pesanan->id_order)
                ->with('error', 'Pesanan dengan status ini tidak bisa dibatalkan');
        }

        // kalau sudah sempat masuk antrean dapur, jadwal produksinya dihapus
        // productionSchedule = relasi di model Order, isinya jadwal dapur milik pesanan ini
        if ($pesanan->productionSchedule) {
            $pesanan->productionSchedule->delete();
        }

        // bukti transfer yang belum dicek ikut ditolak, supaya tidak muncul lagi di halaman validasi
        // payment = relasi di model Order, isinya pembayaran milik pesanan ini
        if ($pesanan->payment && $pesanan->payment->status_verifikasi == 'menunggu') {
            $pesanan->payment->update([
                'status_verifikasi'  => 'ditolak',
                'verified_by'        => Auth::user()->id_user,
                'tanggal_verifikasi' => date('Y-m-d'),
            ]);
        }

        // terakhir, ubah status pesanan jadi dibatalkan
        $pesanan->update([
            'status_pesanan' => 'dibatalkan',
        ]);

        return redirect()->route('admin.pesanan.table')->with('success', 'Pesanan ' . $pesanan->kode_order . ' berhasil dibatalkan');
    }
}
