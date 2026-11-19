<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $table = 'order_items';
    protected $primaryKey = 'id_detail';

    protected $fillable = [
        'id_order',
        'id_paket',
        'jumlah_porsi',
        'harga_satuan',
        'subtotal',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'id_order', 'id_order');
    }

    public function package()
    {
        return $this->belongsTo(CateringPackage::class, 'id_paket', 'id_paket');
    }
}
