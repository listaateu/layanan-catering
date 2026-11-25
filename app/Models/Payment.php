<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// Model untuk tabel payments (bukti transfer dan hasil verifikasi admin)
class Payment extends Model
{
    use HasFactory;

    protected $table = 'payments';
    protected $primaryKey = 'id_payment';

    // fillable = daftar kolom yang boleh diisi lewat create() dan update()
    protected $fillable = [
        'id_order',
        'verified_by',
        'bukti_transfer',
        'jumlah_transfer',
        'status_verifikasi',
        'tanggal_verifikasi',
    ];

    // casts = ubah kolom ini jadi tanggal, supaya bisa dipakai ->format('d-m-Y') di halaman
    protected $casts = [
        'tanggal_verifikasi' => 'date',
    ];

    // hubungan: pembayaran ini milik 1 pesanan
    public function order()
    {
        return $this->belongsTo(Order::class, 'id_order', 'id_order');
    }

    // hubungan: admin yang memverifikasi pembayaran ini (diambil dari tabel users)
    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by', 'id_user');
    }
}
