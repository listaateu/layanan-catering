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
    // index -> menampilkan halaman utama (daftar pesanan milik pelanggan yang sedang login)
    public function index()
    {
        // where('id_customer', ...) = hanya pesanan milik pelanggan ini, bukan punya orang lain
        // Auth::user()->id_user = id pelanggan yang sedang login
        $pesanan = Order::where('id_customer', Auth::user()->id_user)->latest()->get();

        return view('pelanggan.pages.pesanan.pesanan', compact('pesanan'));
    }

    // create -> menampilkan halaman tambah (form pesan untuk paket yang tadi diklik)
    public function create($id)
    {
        // find = cari paket yang dipilih berdasarkan id
        $paket = CateringPackage::find($id);

        // paket yang tidak ada atau sudah dinonaktifkan tidak boleh dipesan
        if (!$paket || $paket->status_aktif != 'aktif') {
            return redirect()->route('pelanggan.menu')->with('error', 'Paket tidak tersedia');
        }

        return view('pelanggan.pages.pesanan.create', compact('paket'));
    }

    // store -> menjalankan fungsi tambah data (dipanggil saat tombol Buat Pesanan ditekan)
    public function store(Request $request)
    {
        // validasi inputan dari form
        // exists = id paket harus ada di tabel catering_packages
        // after_or_equal:today = tanggal acara tidak boleh tanggal yang sudah lewat
        $request->validate([
            'id_paket'      => 'required|exists:catering_packages,id_paket',
            'jumlah_porsi'  => 'required|integer|min:1',
            'tanggal_acara' => 'required|date|after_or_equal:today',
            'jam_kirim'     => 'required',
            'alamat_kirim'  => 'required|string',
        ]);

        // ambil data paket yang dipilih
        $paket = CateringPackage::find($request->id_paket);

        // paket yang sudah dinonaktifkan tidak boleh dipesan
        if ($paket->status_aktif != 'aktif') {
            return redirect()->back()->withInput()->with('error', 'Paket ini sedang tidak tersedia');
        }

        // jumlah porsi tidak boleh kurang dari minimal porsi paket
        if ($request->jumlah_porsi < $paket->min_porsi) {
            return redirect()->back()->withInput()->with('error', 'Minimal pesan paket ini ' . $paket->min_porsi . ' porsi');
        }

        // total bayar = harga per porsi x jumlah porsi
        $total = $paket->harga_porsi * $request->jumlah_porsi;

        // kode pesanan contoh: ORD-20261126-005
        // date('Ymd') = tanggal hari ini | str_pad = tambah angka 0 di depan sampai 3 digit
        // Order::count() + 1 = nomor urut pesanan berikutnya
        $kode = 'ORD-' . date('Ymd') . '-' . str_pad(Order::count() + 1, 3, '0', STR_PAD_LEFT);

        // simpan pesanan baru, statusnya mulai dari menunggu pembayaran
        $pesanan = Order::create([
            'id_customer'    => Auth::user()->id_user,
            'kode_order'     => $kode,
            'tanggal_acara'  => $request->tanggal_acara,
            'jam_kirim'      => $request->jam_kirim,
            'alamat_kirim'   => $request->alamat_kirim,
            'total_bayar'    => $total,
            'status_pesanan' => 'menunggu_pembayaran',
        ]);

        // simpan isi pesanan (paket apa dan berapa porsi) ke tabel order_items
        // harga_satuan disimpan juga, supaya kalau harga paket berubah nanti, pesanan lama tidak ikut berubah
        OrderItem::create([
            'id_order'     => $pesanan->id_order,
            'id_paket'     => $paket->id_paket,
            'jumlah_porsi' => $request->jumlah_porsi,
            'harga_satuan' => $paket->harga_porsi,
            'subtotal'     => $total,
        ]);

        // pindah ke halaman detail pesanan supaya pelanggan bisa langsung upload bukti transfer
        return redirect()->route('pelanggan.pesanan.show', $pesanan->id_order)
            ->with('success', 'Pesanan berhasil dibuat. Silakan transfer lalu upload bukti pembayaran');
    }

    // show -> menampilkan detail satu pesanan (ada form upload bukti transfer dan tombol batal)
    public function show($id)
    {
        // cari pesanan berdasarkan id, tapi harus milik pelanggan yang sedang login
        // first() = ambil 1 data pertama yang cocok
        $pesanan = Order::where('id_order', $id)->where('id_customer', Auth::user()->id_user)->first();

        // kalau tidak ketemu (id salah atau punya orang lain), tampilkan halaman 404
        if (!$pesanan) {
            abort(404);
        }

        return view('pelanggan.pages.pesanan.show', compact('pesanan'));
    }

    // batal -> membatalkan pesanan (pengembalian uang diurus di luar aplikasi)
    // Order $pesanan = Laravel otomatis mencari pesanan sesuai id di alamat URL
    public function batal(Order $pesanan)
    {
        // pesanan orang lain tidak boleh dibatalkan
        if ($pesanan->id_customer != Auth::user()->id_user) {
            abort(403);
        }

        // hanya pesanan yang belum dimasak yang boleh dibatalkan
        if (!in_array($pesanan->status_pesanan, ['menunggu_pembayaran', 'menunggu_verifikasi', 'terkonfirmasi'])) {
            return redirect()->route('pelanggan.pesanan.show', $pesanan->id_order)
                ->with('error', 'Pesanan dengan status ini tidak bisa dibatalkan');
        }

        // kalau sudah sempat masuk antrean dapur, jadwal produksinya dihapus
        if ($pesanan->productionSchedule) {
            $pesanan->productionSchedule->delete();
        }

        // bukti transfer yang belum dicek admin ikut ditolak, supaya tidak muncul lagi di halaman validasi admin
        if ($pesanan->payment && $pesanan->payment->status_verifikasi == 'menunggu') {
            $pesanan->payment->update([
                'status_verifikasi' => 'ditolak',
            ]);
        }

        // terakhir, ubah status pesanan jadi dibatalkan
        $pesanan->update([
            'status_pesanan' => 'dibatalkan',
        ]);

        return redirect()->route('pelanggan.pesanan.table')->with('success', 'Pesanan ' . $pesanan->kode_order . ' berhasil dibatalkan');
    }
}
