<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';
    protected $primaryKey = 'id_order';

    // aturan pembayaran tunai di toko (gampang diubah di sini aja)
    // pelanggan harus datang bayar paling lambat H-2 sebelum tanggal acara, kalau lewat pesanan otomatis dibatalkan
    const BATAS_BAYAR_HARI = 2;

    // pesanan baru minimal H-3 dari hari ini, biar pelanggan masih punya waktu datang ke toko sebelum batas bayar
    const MIN_HARI_PESAN = 3;

    protected $fillable = [
        'id_customer',
        'kode_order',
        'tanggal_acara',
        'jam_kirim',
        'alamat_kirim',
        'total_bayar',
        'status_pesanan',
    ];

    protected $casts = [
        'tanggal_acara' => 'date',
    ];

    // accessor = function yang namanya get...Attribute, bisa dipanggil kayak kolom biasa
    // di halaman tinggal tulis $order->status_label
    // fungsinya ngubah "terkonfirmasi" jadi "Terkonfirmasi" biar rapi (dipakai di halaman admin)
    public function getStatusLabelAttribute()
    {
        if ($this->status_pesanan == 'menunggu_pembayaran') {
            return 'Menunggu Pembayaran';
        }

        return ucwords(str_replace('_', ' ', $this->status_pesanan));
    }

    // dipanggil pakai $order->status_label_pelanggan, khusus halaman pelanggan
    // beda sendiri di status pertama, biar pelanggan tahu harus datang ke toko
    public function getStatusLabelPelangganAttribute()
    {
        if ($this->status_pesanan == 'menunggu_pembayaran') {
            return 'Menunggu Konfirmasi di Toko';
        }

        return $this->status_label;
    }

    // dipanggil pakai $order->batas_bayar
    // hasilnya tanggal terakhir pelanggan boleh datang bayar ke toko (tanggal acara dikurangi 2 hari)
    public function getBatasBayarAttribute()
    {
        return $this->tanggal_acara->copy()->subDays(self::BATAS_BAYAR_HARI);
    }

    // fungsi ini dipanggil lewat nama modelnya: Order::batalkanKadaluarsa()
    // fungsinya membatalkan semua pesanan yang masih menunggu pembayaran tapi sudah lewat batas bayar (H-2 sebelum acara)
    // dipanggil setiap halaman pesanan/pembayaran dibuka, jadi pembatalannya otomatis tanpa perlu jadwal khusus
    // pesanan ini belum dibayar, jadi belum punya jadwal produksi dan ga ada uang yang perlu dikembalikan
    public static function batalkanKadaluarsa()
    {
        // lewat batas bayar = tanggal acara kurang dari (hari ini + 2 hari)
        $batas = date('Y-m-d', strtotime('+' . self::BATAS_BAYAR_HARI . ' days'));

        self::where('status_pesanan', 'menunggu_pembayaran')
            ->where('tanggal_acara', '<', $batas)
            ->update(['status_pesanan' => 'dibatalkan']);
    }

    // bikin kode pesanan baru, contoh: ORD-20261127-001 (nomor urut mulai lagi dari 001 tiap hari)
    // caranya: hitung dulu berapa pesanan hari ini yang sudah punya kode, lalu tambah 1
    public static function buatKode()
    {
        $awal = 'ORD-' . date('Ymd') . '-';

        $urut = self::where('kode_order', 'like', $awal . '%')->count() + 1;

        return $awal . str_pad($urut, 3, '0', STR_PAD_LEFT);
    }

    // yang ini dipanggil pakai $order->status_badge
    // fungsinya nentuin warna label status: kuning (pending), merah (failed), hijau (success)
    public function getStatusBadgeAttribute()
    {
        if ($this->status_pesanan == 'menunggu_pembayaran') {
            return 'pending';
        }

        if ($this->status_pesanan == 'dibatalkan') {
            return 'failed';
        }

        return 'success';
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'id_customer', 'id_user');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class, 'id_order', 'id_order');
    }

    public function payment()
    {
        return $this->hasOne(Payment::class, 'id_order', 'id_order');
    }

    public function productionSchedule()
    {
        return $this->hasOne(ProductionSchedule::class, 'id_order', 'id_order');
    }
}
