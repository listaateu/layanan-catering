<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// Model untuk tabel orders (data pesanan pelanggan)
class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';
    protected $primaryKey = 'id_order';

    // fillable = daftar kolom yang boleh diisi lewat create() dan update()
    protected $fillable = [
        'id_customer',
        'kode_order',
        'tanggal_acara',
        'jam_kirim',
        'alamat_kirim',
        'total_bayar',
        'status_pesanan',
    ];

    // casts = ubah kolom ini jadi tanggal, supaya bisa dipakai ->format('d-m-Y') di halaman
    protected $casts = [
        'tanggal_acara' => 'date',
    ];

    // Function yang namanya get...Attribute bisa dipanggil seperti kolom biasa di halaman: $order->status_label
    // Gunanya: mengubah status "menunggu_pembayaran" jadi tulisan rapi "Menunggu Pembayaran"
    // str_replace = ganti tanda _ dengan spasi | ucwords = huruf depan tiap kata jadi besar
    public function getStatusLabelAttribute()
    {
        return ucwords(str_replace('_', ' ', $this->status_pesanan));
    }

    // Dipanggil di halaman dengan $order->status_badge
    // Gunanya: menentukan warna lencana status (pending = kuning, failed = merah, success = hijau)
    public function getStatusBadgeAttribute()
    {
        if ($this->status_pesanan == 'menunggu_pembayaran' || $this->status_pesanan == 'menunggu_verifikasi') {
            return 'pending';
        }

        if ($this->status_pesanan == 'dibatalkan') {
            return 'failed';
        }

        return 'success';
    }

    // hubungan: pesanan ini milik 1 pelanggan (diambil dari tabel users)
    public function customer()
    {
        return $this->belongsTo(User::class, 'id_customer', 'id_user');
    }

    // hubungan: 1 pesanan punya banyak item (hasMany)
    public function items()
    {
        return $this->hasMany(OrderItem::class, 'id_order', 'id_order');
    }

    // hubungan: 1 pesanan punya 1 pembayaran (hasOne)
    public function payment()
    {
        return $this->hasOne(Payment::class, 'id_order', 'id_order');
    }

    // hubungan: 1 pesanan punya 1 jadwal produksi di dapur
    public function productionSchedule()
    {
        return $this->hasOne(ProductionSchedule::class, 'id_order', 'id_order');
    }
}
