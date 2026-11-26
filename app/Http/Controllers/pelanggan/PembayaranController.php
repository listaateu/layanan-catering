<?php

namespace App\Http\Controllers\pelanggan;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PembayaranController extends Controller
{
    // store -> menjalankan fungsi upload bukti transfer (dipanggil saat tombol Kirim Bukti ditekan)
    // Order $pesanan = Laravel otomatis mencari pesanan sesuai id di alamat URL
    public function store(Request $request, Order $pesanan)
    {
        // pesanan orang lain tidak boleh diisi bukti transfernya
        if ($pesanan->id_customer != Auth::user()->id_user) {
            abort(403);
        }

        // bukti transfer hanya boleh dikirim kalau pesanan masih menunggu pembayaran
        if ($pesanan->status_pesanan != 'menunggu_pembayaran') {
            return redirect()->route('pelanggan.pesanan.show', $pesanan->id_order)
                ->with('error', 'Pesanan ini tidak sedang menunggu pembayaran');
        }

        // validasi inputan dari form
        // image|mimes = harus berupa gambar jpg/jpeg/png, max:2048 = maksimal 2 MB
        $request->validate([
            'bukti_transfer'  => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'jumlah_transfer' => 'required|numeric|min:1',
        ]);

        // nama file dibuat dari kode pesanan, contoh: ord-20261126-005-1732420000.jpg
        // Str::slug() = ubah teks jadi format url | time() = supaya nama file tidak bentrok
        $filename = Str::slug($pesanan->kode_order, '-') . '-' . time() . '.' . $request->file('bukti_transfer')->getClientOriginalExtension();

        // storeAs() = simpan file ke storage/app/public/bukti dengan nama yang kita tentukan
        // yang disimpan di database hanya path-nya, contoh: bukti/ord-20261126-005-1732420000.jpg
        $bukti = $request->file('bukti_transfer')->storeAs('bukti', $filename, 'public');

        if ($pesanan->payment) {
            // pembayaran pernah ditolak admin, jadi datanya diperbarui (1 pesanan hanya punya 1 pembayaran)
            // status kembali ke menunggu, kolom verifikasi dikosongkan lagi supaya admin cek ulang
            $pesanan->payment->update([
                'bukti_transfer'     => $bukti,
                'jumlah_transfer'    => $request->jumlah_transfer,
                'status_verifikasi'  => 'menunggu',
                'verified_by'        => null,
                'tanggal_verifikasi' => null,
            ]);
        } else {
            // pembayaran pertama kali, simpan data baru ke tabel payments
            Payment::create([
                'id_order'          => $pesanan->id_order,
                'bukti_transfer'    => $bukti,
                'jumlah_transfer'   => $request->jumlah_transfer,
                'status_verifikasi' => 'menunggu',
            ]);
        }

        // status pesanan berubah, sekarang giliran admin yang mengecek bukti transfernya
        $pesanan->update([
            'status_pesanan' => 'menunggu_verifikasi',
        ]);

        return redirect()->route('pelanggan.pesanan.show', $pesanan->id_order)
            ->with('success', 'Bukti transfer berhasil dikirim, tunggu admin mengecek pembayaranmu');
    }
}
