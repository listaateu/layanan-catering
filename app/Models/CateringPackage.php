<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CateringPackage extends Model
{
    use HasFactory;

    protected $table = 'catering_packages';
    protected $primaryKey = 'id_paket';

    protected $fillable = [
        'nama_paket',
        'deskripsi',
        'harga_porsi',
        'min_porsi',
        'gambar_paket',
        'status_aktif',
    ];

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'id_paket', 'id_paket');
    }
}
