<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';
    protected $primaryKey = 'id_order';

    protected $fillable = [
        'id_customer',
        'kode_order',
        'tanggal_acara',
        'jam_kirim',
        'alamat_kirim',
        'total_bayar',
        'status_pesanan',
    ];

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
