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

    public function order()
    {
        return $this->belongsTo(Order::class, 'id_order', 'id_order');
    }

    public function handler()
    {
        return $this->belongsTo(User::class, 'handled_by', 'id_user');
    }
}
