<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use App\Models\ProductionSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PembayaranController extends Controller
{
    //index -> tampilin 2 tabel: pesanan yang belum bayar dan pembayaran tunai yang sudah diterima
    //bisa dicari berdasarkan kode order, biar kasir tinggal ketik kode yang disebutkan pelanggan
    public function index(Request $request)
    {
        // pesanan yang lewat batas bayar dibatalkan dulu, biar tidak muncul di daftar belum bayar
        Order::batalkanKadaluarsa();

        $cari = $request->cari;

        $queryBelumBayar = Order::where('status_pesanan', 'menunggu_pembayaran')->orderBy('tanggal_acara');
        $queryPembayaran = Payment::latest();

        if ($cari) {
            $queryBelumBayar->where('kode_order', 'like', '%' . $cari . '%');
            // kode order ada di tabel orders, makanya dicari lewat relasi order
            $queryPembayaran->whereHas('order', function ($q) use ($cari) {
                $q->where('kode_order', 'like', '%' . $cari . '%');
            });
        }

        $belumBayar = $queryBelumBayar->get();
        $pembayaran = $queryPembayaran->get();

        return view('admin.pages.pembayaran.pembayaran', compact('belumBayar', 'pembayaran', 'cari'));
    }

    //create -> tampilin form terima pembayaran tunai
    public function create($id)
    {
        Order::batalkanKadaluarsa();

        $pesanan = Order::find($id);

        // kalau sudah dibayar atau dibatalkan, ga perlu form lagi
        if ($pesanan->status_pesanan != 'menunggu_pembayaran') {
            return redirect()->route('admin.pembayaran.table')->with('error', 'Pesanan ini tidak sedang menunggu pembayaran');
        }

        return view('admin.pages.pembayaran.create', compact('pesanan'));
    }

    //store -> simpan pembayaran tunai, pesanan langsung masuk antrean dapur
    public function store(Request $request, Order $pesanan)
    {
        Order::batalkanKadaluarsa();
        $pesanan->refresh();

        $request->validate([
            'jumlah_bayar'  => 'required|numeric|min:1',
            'tanggal_bayar' => 'required|date|before_or_equal:today',
        ]);

        if ($pesanan->status_pesanan != 'menunggu_pembayaran') {
            return redirect()->route('admin.pembayaran.table')->with('error', 'Pesanan ini tidak sedang menunggu pembayaran');
        }

        // uang yang diterima harus pas sama total pesanan
        if ($request->jumlah_bayar != $pesanan->total_bayar) {
            return redirect()->back()->withInput()->with('error', 'Jumlah bayar harus sama dengan total pesanan');
        }

        Payment::create([
            'id_order'      => $pesanan->id_order,
            'verified_by'   => Auth::user()->id_user,
            'jumlah_bayar'  => $request->jumlah_bayar,
            'tanggal_bayar' => $request->tanggal_bayar,
        ]);

        $pesanan->update([
            'status_pesanan' => 'terkonfirmasi',
        ]);

        // dibuatin jadwal produksi biar muncul di antrean dapur
        ProductionSchedule::create([
            'id_order'        => $pesanan->id_order,
            'status_produksi' => 'antrean_dapur',
        ]);

        return redirect()->route('admin.pembayaran.table')->with('success', 'Pembayaran ' . $pesanan->kode_order . ' diterima, pesanan masuk antrean dapur');
    }

    //show -> tampilin detail pembayaran
    public function show($id)
    {
        $pembayaran = Payment::find($id);

        return view('admin.pages.pembayaran.show', compact('pembayaran'));
    }
}
