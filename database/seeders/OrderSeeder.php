<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\CateringPackage;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\ProductionSchedule;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil data yang sudah ada di database (dari UserSeeder & CateringPackageSeeder)
        $pelanggan = User::where('role', 'pelanggan')->first();
        $kasir = User::where('role', 'admin_kasir')->first();
        $dapur = User::where('role', 'kepala_dapur')->first();

        $nasiBox = CateringPackage::where('nama_paket', 'Paket Nasi Box Ayam')->first();
        $prasmanan = CateringPackage::where('nama_paket', 'Paket Prasmanan')->first();
        $snackBox = CateringPackage::where('nama_paket', 'Paket Snack Box')->first();

        // ===== Pesanan 1: baru dibuat, belum bayar =====
        $order1 = Order::create([
            'id_customer' => $pelanggan->id_user,
            'kode_order' => 'ORD-20261123-001',
            'tanggal_acara' => '2026-11-27',
            'jam_kirim' => '11:00',
            'alamat_kirim' => 'Jl. Contoh No. 1, Surakarta',
            'total_bayar' => 750000,
            'status_pesanan' => 'menunggu_pembayaran'
        ]);
        OrderItem::create([
            'id_order' => $order1->id_order,
            'id_paket' => $nasiBox->id_paket,
            'jumlah_porsi' => 30,
            'harga_satuan' => 25000,
            'subtotal' => 750000
        ]);

        // ===== Pesanan 2: sudah upload bukti transfer, menunggu dicek admin =====
        $order2 = Order::create([
            'id_customer' => $pelanggan->id_user,
            'kode_order' => 'ORD-20261123-002',
            'tanggal_acara' => '2026-11-28',
            'jam_kirim' => '12:00',
            'alamat_kirim' => 'Jl. Contoh No. 2, Surakarta',
            'total_bayar' => 2700000,
            'status_pesanan' => 'menunggu_verifikasi'
        ]);
        OrderItem::create([
            'id_order' => $order2->id_order,
            'id_paket' => $prasmanan->id_paket,
            'jumlah_porsi' => 60,
            'harga_satuan' => 45000,
            'subtotal' => 2700000
        ]);
        Payment::create([
            'id_order' => $order2->id_order,
            'bukti_transfer' => 'bukti/contoh-2.jpg',
            'jumlah_transfer' => 2700000,
            'status_verifikasi' => 'menunggu'
        ]);

        // ===== Pesanan 3: pembayaran diterima, masuk antrean dapur =====
        $order3 = Order::create([
            'id_customer' => $pelanggan->id_user,
            'kode_order' => 'ORD-20261123-003',
            'tanggal_acara' => '2026-11-29',
            'jam_kirim' => '10:00',
            'alamat_kirim' => 'Jl. Contoh No. 3, Surakarta',
            'total_bayar' => 600000,
            'status_pesanan' => 'terkonfirmasi'
        ]);
        OrderItem::create([
            'id_order' => $order3->id_order,
            'id_paket' => $snackBox->id_paket,
            'jumlah_porsi' => 40,
            'harga_satuan' => 15000,
            'subtotal' => 600000
        ]);
        Payment::create([
            'id_order' => $order3->id_order,
            'verified_by' => $kasir->id_user,
            'bukti_transfer' => 'bukti/contoh-3.jpg',
            'jumlah_transfer' => 600000,
            'status_verifikasi' => 'diterima',
            'tanggal_verifikasi' => '2026-11-23'
        ]);
        ProductionSchedule::create([
            'id_order' => $order3->id_order,
            'status_produksi' => 'antrean_dapur'
        ]);

        // ===== Pesanan 4: sedang dimasak oleh kepala dapur =====
        $order4 = Order::create([
            'id_customer' => $pelanggan->id_user,
            'kode_order' => 'ORD-20261123-004',
            'tanggal_acara' => '2026-11-30',
            'jam_kirim' => '11:30',
            'alamat_kirim' => 'Jl. Contoh No. 4, Surakarta',
            'total_bayar' => 1250000,
            'status_pesanan' => 'diproses'
        ]);
        OrderItem::create([
            'id_order' => $order4->id_order,
            'id_paket' => $nasiBox->id_paket,
            'jumlah_porsi' => 50,
            'harga_satuan' => 25000,
            'subtotal' => 1250000
        ]);
        Payment::create([
            'id_order' => $order4->id_order,
            'verified_by' => $kasir->id_user,
            'bukti_transfer' => 'bukti/contoh-4.jpg',
            'jumlah_transfer' => 1250000,
            'status_verifikasi' => 'diterima',
            'tanggal_verifikasi' => '2026-11-23'
        ]);
        ProductionSchedule::create([
            'id_order' => $order4->id_order,
            'handled_by' => $dapur->id_user,
            'status_produksi' => 'sedang_dimasak'
        ]);
    }
}
