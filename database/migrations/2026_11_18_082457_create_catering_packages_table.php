<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('catering_packages', function (Blueprint $table) {
            $table->id('id_paket');
            $table->string('nama_paket');
            $table->text('deskripsi')->nullable();
            $table->decimal('harga_porsi', 12, 2);
            $table->unsignedInteger('min_porsi')->default(1);
            $table->string('gambar_paket')->nullable();
            $table->enum('status_aktif', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('catering_packages');
    }
};
