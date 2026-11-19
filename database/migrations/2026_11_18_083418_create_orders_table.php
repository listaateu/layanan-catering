<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id('id_order');
            $table->foreignId('id_customer')->constrained('users', 'id_user')->cascadeOnDelete();
            $table->string('kode_order')->unique();
            $table->date('tanggal_acara');
            $table->time('jam_kirim');
            $table->text('alamat_kirim');
            $table->decimal('total_bayar', 12, 2)->default(0);
            $table->enum('status_pesanan', ['menunggu_pembayaran', 'menunggu_verifikasi', 'terkonfirmasi', 'diproses', 'selesai', 'dibatalkan'])->default('menunggu_pembayaran');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
