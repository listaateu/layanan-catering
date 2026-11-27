<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductionSchedule extends Model
{
    use HasFactory;

    protected $table = 'production_schedules';
    protected $primaryKey = 'id_produksi';

    protected $fillable = [
        'id_order',
        'handled_by',
        'status_produksi',
    ];

    // accessor = function get...Attribute, dipanggil kayak kolom: $produksi->status_label
    // fungsinya ngubah "sedang_dimasak" jadi "Sedang Dimasak"
    public function getStatusLabelAttribute()
    {
        return ucwords(str_replace('_', ' ', $this->status_produksi));
    }

    // dipanggil pakai $produksi->status_badge
    // fungsinya nentuin warna label: kuning (pending) kalau belum jadi, hijau (success) kalau sudah
    public function getStatusBadgeAttribute()
    {
        if ($this->status_produksi == 'antrean_dapur' || $this->status_produksi == 'sedang_dimasak') {
            return 'pending';
        }

        return 'success';
    }

    // scope = potongan query yang bisa dipakai ulang, dipanggil: ProductionSchedule::urutAntrean()->get()
    // fungsinya mengurutkan antrean dari tanggal acara paling dekat, kalau tanggalnya sama dari jam kirim paling pagi
    // tanggal dan jam ada di tabel orders, makanya tabelnya digabung (join) dulu
    public function scopeUrutAntrean($query)
    {
        return $query->join('orders', 'orders.id_order', '=', 'production_schedules.id_order')
            ->orderBy('orders.tanggal_acara')
            ->orderBy('orders.jam_kirim')
            ->select('production_schedules.*');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'id_order', 'id_order');
    }

    public function handler()
    {
        return $this->belongsTo(User::class, 'handled_by', 'id_user');
    }
}
