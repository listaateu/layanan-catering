<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('production_schedules', function (Blueprint $table) {
            $table->id('id_produksi');
            $table->foreignId('id_order')->unique()->constrained('orders', 'id_order')->cascadeOnDelete();
            $table->foreignId('handled_by')->nullable()->constrained('users', 'id_user');
            $table->enum('status_produksi', ['antrean_dapur', 'sedang_dimasak', 'siap_kirim', 'terkirim'])->default('antrean_dapur');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('production_schedules');
    }
};
