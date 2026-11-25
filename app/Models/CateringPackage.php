<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// Model = penghubung antara kode Laravel dengan tabel di database
// Model ini untuk tabel catering_packages (daftar paket menu)
class CateringPackage extends Model
{
    use HasFactory;

    // nama tabel di database
    protected $table = 'catering_packages';
    // nama kolom primary key (kunci utama), karena bukan 'id' biasa
    protected $primaryKey = 'id_paket';

    // fillable = daftar kolom yang boleh diisi lewat create() dan update()
    protected $fillable = [
        'nama_paket',
        'deskripsi',
        'harga_porsi',
        'min_porsi',
        'gambar_paket',
        'status_aktif',
    ];

    // hubungan: 1 paket bisa dipakai di banyak item pesanan (hasMany = punya banyak)
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'id_paket', 'id_paket');
    }
}
