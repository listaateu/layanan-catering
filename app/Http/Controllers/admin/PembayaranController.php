<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\ProductionSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PembayaranController extends Controller
{
    public function index()
    {
        // yang masih menunggu ditampilkan paling atas
        $pembayaran = Payment::with('order.customer')
            ->orderByRaw("status_verifikasi = 'menunggu' DESC")
            ->latest()
            ->get();

        return view('admin.pages.pembayaran.pembayaran', compact('pembayaran'));
    }

    public function edit(Payment $pembayaran)
    {
        $pembayaran->load('order.customer', 'order.items.package', 'verifier');
        return view('admin.pages.pembayaran.validasi', compact('pembayaran'));
    }

    public function update(Request $request, Payment $pembayaran)
    {
        $request->validate([
            'status_verifikasi' => 'required|in:diterima,ditolak',
        ]);

        if ($pembayaran->status_verifikasi !== 'menunggu') {
            return redirect()->route('admin.pembayaran')
                ->with('error', 'Pembayaran ini sudah diverifikasi sebelumnya');
        }

        DB::transaction(function () use ($request, $pembayaran) {
            $pembayaran->update([
                'status_verifikasi' => $request->status_verifikasi,
                'verified_by' => auth()->id(),
                'tanggal_verifikasi' => now()->toDateString(),
            ]);

            $order = $pembayaran->order;

            if ($request->status_verifikasi === 'diterima') {
                $order->update(['status_pesanan' => 'terkonfirmasi']);

                // otomatis masuk antrean dapur
                ProductionSchedule::firstOrCreate(
                    ['id_order' => $order->id_order],
                    ['status_produksi' => 'antrean_dapur']
                );
            } else {
                // ditolak -> pelanggan harus upload ulang bukti transfer
                $order->update(['status_pesanan' => 'menunggu_pembayaran']);
            }
        });

        $pesan = $request->status_verifikasi === 'diterima'
            ? 'Pembayaran diterima, pesanan masuk antrean dapur'
            : 'Pembayaran ditolak, pelanggan diminta upload ulang bukti transfer';

        return redirect()->route('admin.pembayaran')->with('success', $pesan);
    }
}
