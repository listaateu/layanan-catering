<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

    public function show($id)
    {
        $pesanan = Order::with('customer', 'items.package', 'payment', 'productionSchedule')->findOrFail($id);
        return view('admin.pages.pesanan.show', compact('pesanan'));
    }

    public function batal(Order $pesanan)
    {
        if (!in_array($pesanan->status_pesanan, ['menunggu_pembayaran', 'menunggu_verifikasi', 'terkonfirmasi'])) {
            return redirect()->route('admin.pesanan.show', $pesanan->id_order)
                ->with('error', 'Pesanan dengan status ini tidak bisa dibatalkan');
        }

        DB::transaction(function () use ($pesanan) {
            // kalau sudah sempat masuk antrean dapur, jadwalnya dihapus
            $pesanan->productionSchedule?->delete();

            // bukti transfer yang belum dicek ikut ditutup, supaya tidak muncul lagi di validasi
            if ($pesanan->payment && $pesanan->payment->status_verifikasi === 'menunggu') {
                $pesanan->payment->update([
                    'status_verifikasi' => 'ditolak',
                    'verified_by' => auth()->id(),
                    'tanggal_verifikasi' => now()->toDateString(),
                ]);
            }

            $pesanan->update(['status_pesanan' => 'dibatalkan']);
        });

        return redirect()->route('admin.pesanan')->with('success', 'Pesanan ' . $pesanan->kode_order . ' berhasil dibatalkan');
    }
}
