<?php

namespace App\Http\Controllers\dapur;

use App\Http\Controllers\Controller;
use App\Models\ProductionSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProduksiController extends Controller
{
    //index -> tampilin antrean dapur (yang sudah terkirim ada di menu Riwayat)
    public function index(Request $request)
    {
        $status = $request->status;

        if ($status) {
            $produksi = ProductionSchedule::where('status_produksi', $status)->get();
        } else {
            $produksi = ProductionSchedule::where('status_produksi', '!=', 'terkirim')->get();
        }

        // sortBy = urutin hasilnya, sama seperti di dashboard dapur
        // diurutkan dari tanggal acara paling dekat, kalau tanggalnya sama dari jam kirim paling pagi
        $produksi = $produksi->sortBy(function ($item) {
            return $item->order->tanggal_acara->format('Y-m-d') . ' ' . $item->order->jam_kirim;
        });

        return view('dapur.pages.produksi.produksi', compact('produksi', 'status'));
    }

    //edit -> tampilin form ubah status
    public function edit($id)
    {
        $produksi = ProductionSchedule::find($id);

        // status berikutnya yang boleh dipilih, harus urut ga boleh loncat
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

    //update -> simpan perubahan status
    public function update(Request $request, ProductionSchedule $produksi)
    {
        $request->validate([
            'status_produksi' => 'required|in:sedang_dimasak,siap_kirim,terkirim',
        ]);

        // status baru harus langkah selanjutnya, ga boleh loncat atau mundur
        $urutan = [
            'antrean_dapur' => 'sedang_dimasak',
            'sedang_dimasak' => 'siap_kirim',
            'siap_kirim' => 'terkirim',
        ];

        if (!isset($urutan[$produksi->status_produksi]) || $urutan[$produksi->status_produksi] != $request->status_produksi) {
            return redirect()->route('dapur.produksi.table')->with('error', 'Status harus diubah berurutan');
        }

        // handled_by diisi kepala dapur yang lagi login
        $produksi->update([
            'status_produksi' => $request->status_produksi,
            'handled_by'      => Auth::user()->id_user,
        ]);

        // status pesanan ikut berubah biar admin dan pelanggan bisa lihat
        if ($request->status_produksi == 'sedang_dimasak') {
            $produksi->order->update(['status_pesanan' => 'diproses']);
        }

        if ($request->status_produksi == 'terkirim') {
            $produksi->order->update(['status_pesanan' => 'selesai']);
        }

        return redirect()->route('dapur.produksi.table')->with('success', 'Status produksi ' . $produksi->order->kode_order . ' berhasil diubah');
    }
}
