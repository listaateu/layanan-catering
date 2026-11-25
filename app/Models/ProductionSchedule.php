<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// Model untuk tabel production_schedules (antrean dan progres masakan di dapur)
class ProductionSchedule extends Model
{
    use HasFactory;

    protected $table = 'production_schedules';
    protected $primaryKey = 'id_produksi';

    // fillable = daftar kolom yang boleh diisi lewat create() dan update()
    protected $fillable = [
        'id_order',
        'handled_by',
        'status_produksi',
    ];

    // Dipanggil di halaman dengan $produksi->status_label
    // Gunanya: mengubah "sedang_dimasak" jadi tulisan rapi "Sedang Dimasak"
    public function getStatusLabelAttribute()
    {
        return ucwords(str_replace('_', ' ', $this->status_produksi));
    }

    // Dipanggil di halaman dengan $produksi->status_badge
    // Gunanya: menentukan warna lencana status (pending = kuning, success = hijau)
    public function getStatusBadgeAttribute()
    {
        if ($this->status_produksi == 'antrean_dapur' || $this->status_produksi == 'sedang_dimasak') {
            return 'pending';
        }

        return 'success';
    }

    // hubungan: jadwal produksi ini milik 1 pesanan
    public function order()
    {
        return $this->belongsTo(Order::class, 'id_order', 'id_order');
    }

    // hubungan: kepala dapur yang menangani (diambil dari tabel users)
    public function handler()
    {
        return $this->belongsTo(User::class, 'handled_by', 'id_user');
    }
}
