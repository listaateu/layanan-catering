<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// Model untuk tabel order_items (isi pesanan: paket apa, berapa porsi)
class OrderItem extends Model
{
    use HasFactory;

    protected $table = 'order_items';
    protected $primaryKey = 'id_detail';

    // fillable = daftar kolom yang boleh diisi lewat create() dan update()
    protected $fillable = [
        'id_order',
        'id_paket',
        'jumlah_porsi',
        'harga_satuan',
        'subtotal',
    ];

    // hubungan: item ini milik 1 pesanan (belongsTo = milik)
    public function order()
    {
        return $this->belongsTo(Order::class, 'id_order', 'id_order');
    }

    // hubungan: item ini memilih 1 paket menu
    public function package()
    {
        return $this->belongsTo(CateringPackage::class, 'id_paket', 'id_paket');
    }
}
