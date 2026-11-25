<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\ProductionSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PembayaranController extends Controller
{
    // index -> menampilkan halaman utama (daftar pembayaran)
    public function index()
    {
        // latest() = yang paling baru ada di atas
        $pembayaran = Payment::latest()->get();
        return view('admin.pages.pembayaran.pembayaran', compact('pembayaran'));
    }

    // edit -> menampilkan halaman validasi bukti transfer
    public function edit($id)
    {
        // find = cari 1 data pembayaran berdasarkan id
        $pembayaran = Payment::find($id);

        // yang sudah diverifikasi (atau pesanannya sudah dibatalkan) cukup dilihat saja
        // order = relasi di model Payment, untuk mengambil data pesanan dari pembayaran ini
        if ($pembayaran->status_verifikasi != 'menunggu' || $pembayaran->order->status_pesanan != 'menunggu_verifikasi') {
            return redirect()->route('admin.pembayaran.show', $id);
        }

        return view('admin.pages.pembayaran.validasi', compact('pembayaran'));
    }

    // update -> menyimpan hasil validasi (diterima / ditolak)
    // Payment $pembayaran = Laravel otomatis mencari pembayaran sesuai id di alamat URL
    public function update(Request $request, Payment $pembayaran)
    {
        // pilihan hanya boleh diterima atau ditolak
        $request->validate([
            'status_verifikasi' => 'required|in:diterima,ditolak',
        ]);

        // pembayaran yang sudah diverifikasi tidak boleh diubah lagi
        if ($pembayaran->status_verifikasi != 'menunggu') {
            return redirect()->route('admin.pembayaran.table')
                ->with('error', 'Pembayaran ini sudah diverifikasi sebelumnya');
        }

        // pesanan yang sudah dibatalkan tidak boleh dihidupkan lagi lewat validasi
        if ($pembayaran->order->status_pesanan != 'menunggu_verifikasi') {
            return redirect()->route('admin.pembayaran.table')
                ->with('error', 'Pesanan ini tidak sedang menunggu verifikasi');
        }

        // simpan hasil validasi di data pembayaran
        // Auth::user()->id_user = id admin yang sedang login (siapa yang memverifikasi)
        // date('Y-m-d') = tanggal hari ini
        $pembayaran->update([
            'status_verifikasi'  => $request->status_verifikasi,
            'verified_by'        => Auth::user()->id_user,
            'tanggal_verifikasi' => date('Y-m-d'),
        ]);

        if ($request->status_verifikasi == 'diterima') {
            // diterima -> pesanan terkonfirmasi dan otomatis masuk antrean dapur
            $pembayaran->order->update([
                'status_pesanan' => 'terkonfirmasi',
            ]);

            // buat jadwal produksi baru supaya muncul di antrean dapur
            ProductionSchedule::create([
                'id_order'        => $pembayaran->id_order,
                'status_produksi' => 'antrean_dapur',
            ]);

            $pesan = 'Pembayaran diterima, pesanan masuk antrean dapur';
        } else {
            // ditolak -> status pesanan kembali ke menunggu pembayaran, pelanggan harus upload ulang bukti transfer
            $pembayaran->order->update([
                'status_pesanan' => 'menunggu_pembayaran',
            ]);

            $pesan = 'Pembayaran ditolak, pelanggan diminta upload ulang bukti transfer';
        }

        return redirect()->route('admin.pembayaran.table')->with('success', $pesan);
    }

    // show -> menampilkan detail pembayaran (hanya dilihat, tidak bisa diubah)
    public function show($id)
    {
        $pembayaran = Payment::find($id);
        return view('admin.pages.pembayaran.show', compact('pembayaran'));
    }
}
