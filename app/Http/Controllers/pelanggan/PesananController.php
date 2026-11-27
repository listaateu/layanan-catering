<?php

namespace App\Http\Controllers\pelanggan;

use App\Http\Controllers\Controller;
use App\Models\CateringPackage;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PesananController extends Controller
{
    //index -> tampilin daftar pesanan milik pelanggan yang lagi login
    public function index()
    {
        // pesanan yang lewat batas bayar dibatalkan dulu, biar statusnya yang tampil sudah terbaru
        Order::batalkanKadaluarsa();

        $pesanan = Order::where('id_customer', Auth::user()->id_user)->latest()->get();

        return view('pelanggan.pages.pesanan.pesanan', compact('pesanan'));
    }

    //create -> tampilin form pesan untuk paket yang dipilih
    public function create($id)
    {
        $paket = CateringPackage::find($id);

        // paket ga ada atau nonaktif ga boleh dipesan
        if (!$paket || $paket->status_aktif != 'aktif') {
            return redirect()->route('pelanggan.menu')->with('error', 'Paket tidak tersedia');
        }

        return view('pelanggan.pages.pesanan.create', compact('paket'));
    }

    //store -> simpan pesanan baru
    public function store(Request $request)
    {
        // tanggal acara minimal 3 hari dari sekarang, biar pelanggan masih sempat datang bayar ke toko (batas bayar H-2)
        $tanggalMinimal = date('Y-m-d', strtotime('+' . Order::MIN_HARI_PESAN . ' days'));

        $request->validate([
            'id_paket'      => 'required|exists:catering_packages,id_paket',
            'jumlah_porsi'  => 'required|integer|min:1',
            'tanggal_acara' => 'required|date|after_or_equal:' . $tanggalMinimal,
            'jam_kirim'     => 'required',
            'alamat_kirim'  => 'required|string',
        ], [
            'tanggal_acara.after_or_equal' => 'Tanggal acara minimal ' . Order::MIN_HARI_PESAN . ' hari dari hari ini (' . date('d-m-Y', strtotime($tanggalMinimal)) . ')',
        ]);

        $paket = CateringPackage::find($request->id_paket);

        if ($paket->status_aktif != 'aktif') {
            return redirect()->back()->withInput()->with('error', 'Paket ini sedang tidak tersedia');
        }

        if ($request->jumlah_porsi < $paket->min_porsi) {
            return redirect()->back()->withInput()->with('error', 'Minimal pesan paket ini ' . $paket->min_porsi . ' porsi');
        }

        // total = harga per porsi x jumlah porsi
        $total = $paket->harga_porsi * $request->jumlah_porsi;

        // kode pesanan dibuat otomatis, contoh: ORD-20261127-001
        $pesanan = Order::create([
            'id_customer'    => Auth::user()->id_user,
            'kode_order'     => Order::buatKode(),
            'tanggal_acara'  => $request->tanggal_acara,
            'jam_kirim'      => $request->jam_kirim,
            'alamat_kirim'   => $request->alamat_kirim,
            'total_bayar'    => $total,
            'status_pesanan' => 'menunggu_pembayaran',
        ]);

        // harga_satuan ikut disimpan, biar kalau harga paket berubah pesanan lama tetap aman
        OrderItem::create([
            'id_order'     => $pesanan->id_order,
            'id_paket'     => $paket->id_paket,
            'jumlah_porsi' => $request->jumlah_porsi,
            'harga_satuan' => $paket->harga_porsi,
            'subtotal'     => $total,
        ]);

        return redirect()->route('pelanggan.pesanan.show', $pesanan->id_order)
            ->with('success', 'Pesanan berhasil dibuat. Datang ke toko dan bayar tunai paling lambat ' . $pesanan->batas_bayar->format('d-m-Y') . ', sebutkan kode ' . $pesanan->kode_order);
    }

    //show -> tampilin detail satu pesanan
    public function show($id)
    {
        Order::batalkanKadaluarsa();

        // harus pesanan milik sendiri
        $pesanan = Order::where('id_order', $id)->where('id_customer', Auth::user()->id_user)->first();

        if (!$pesanan) {
            abort(404);
        }

        return view('pelanggan.pages.pesanan.show', compact('pesanan'));
    }

    //batal -> batalin pesanan, hanya boleh selama belum dibayar
    //kalau sudah dibayar tunai, pembatalan lewat admin di toko karena uangnya harus dikembalikan langsung
    public function batal(Order $pesanan)
    {
        // pesanan orang lain ga boleh dibatalin
        if ($pesanan->id_customer != Auth::user()->id_user) {
            abort(403);
        }

        if ($pesanan->status_pesanan != 'menunggu_pembayaran') {
            $pesan = $pesanan->status_pesanan == 'dibatalkan'
                ? 'Pesanan ini sudah dibatalkan'
                : 'Pesanan yang sudah dibayar hanya bisa dibatalkan lewat admin di toko';

            return redirect()->route('pelanggan.pesanan.show', $pesanan->id_order)->with('error', $pesan);
        }

        $pesanan->update([
            'status_pesanan' => 'dibatalkan',
        ]);

        return redirect()->route('pelanggan.pesanan.table')->with('success', 'Pesanan ' . $pesanan->kode_order . ' berhasil dibatalkan');
    }
}
