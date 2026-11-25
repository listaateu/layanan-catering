<?php

namespace App\Http\Controllers\dapur;

use App\Http\Controllers\Controller;
use App\Models\ProductionSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProduksiController extends Controller
{
    // index -> menampilkan halaman utama (antrean yang harus dikerjakan dapur)
    // yang sudah terkirim tidak tampil di sini, adanya di menu Riwayat
    public function index(Request $request)
    {
        // ambil status dari alamat, contoh: ?status=sedang_dimasak
        $status = $request->status;

        // kalau ada status yang dipilih, tampilkan yang statusnya sama saja
        // kalau tidak ada, tampilkan semua yang belum terkirim
        if ($status) {
            $produksi = ProductionSchedule::where('status_produksi', $status)->get();
        } else {
            $produksi = ProductionSchedule::where('status_produksi', '!=', 'terkirim')->get();
        }

        return view('dapur.pages.produksi.produksi', compact('produksi', 'status'));
    }

    // edit -> menampilkan halaman update status (detail pesanan + form ubah status)
    public function edit($id)
    {
        // find = cari 1 data jadwal produksi berdasarkan id
        $produksi = ProductionSchedule::find($id);

        // status berikutnya yang boleh dipilih (dapur bekerja urut, tidak boleh loncat)
        // antrean_dapur -> sedang_dimasak -> siap_kirim -> terkirim
        $statusBerikut = null;
        if ($produksi->status_produksi == 'antrean_dapur') {
            $statusBerikut = 'sedang_dimasak';
        } elseif ($produksi->status_produksi == 'sedang_dimasak') {
            $statusBerikut = 'siap_kirim';
        } elseif ($produksi->status_produksi == 'siap_kirim') {
            $statusBerikut = 'terkirim';
        }

        return view('dapur.pages.produksi.update', compact('produksi', 'statusBerikut'));
    }

    // update -> menjalankan fungsi ubah status (dipanggil saat tombol Simpan ditekan)
    // ProductionSchedule $produksi = Laravel otomatis mencari jadwal sesuai id di alamat URL
    public function update(Request $request, ProductionSchedule $produksi)
    {
        // validasi inputan dari form
        // in:... = isinya hanya boleh salah satu dari pilihan itu
        $request->validate([
            'status_produksi' => 'required|in:sedang_dimasak,siap_kirim,terkirim',
        ]);

        // cek urutan: status baru harus langkah berikutnya, tidak boleh loncat atau mundur
        // contoh: antrean_dapur tidak boleh langsung ke terkirim
        $urutan = [
            'antrean_dapur' => 'sedang_dimasak',
            'sedang_dimasak' => 'siap_kirim',
            'siap_kirim' => 'terkirim',
        ];

        // isset() = cek apakah status sekarang ada di daftar urutan (terkirim sudah tidak ada langkah lagi)
        if (!isset($urutan[$produksi->status_produksi]) || $urutan[$produksi->status_produksi] != $request->status_produksi) {
            return redirect()->route('dapur.produksi.table')->with('error', 'Status harus diubah berurutan');
        }

        // update status produksi, dan catat kepala dapur yang sedang login sebagai penanggung jawab
        // Auth::user()->id_user = id user yang sedang login
        $produksi->update([
            'status_produksi' => $request->status_produksi,
            'handled_by'      => Auth::user()->id_user,
        ]);

        // status pesanan ikut berubah supaya admin dan pelanggan bisa memantau
        // sedang_dimasak -> pesanan "diproses" | terkirim -> pesanan "selesai"
        // $produksi->order = pesanan milik jadwal ini (relasi di model)
        if ($request->status_produksi == 'sedang_dimasak') {
            $produksi->order->update(['status_pesanan' => 'diproses']);
        }

        if ($request->status_produksi == 'terkirim') {
            $produksi->order->update(['status_pesanan' => 'selesai']);
        }

        return redirect()->route('dapur.produksi.table')->with('success', 'Status produksi ' . $produksi->order->kode_order . ' berhasil diubah');
    }
}
