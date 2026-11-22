<?php

namespace Database\Seeders;

use App\Models\CateringPackage;
use Illuminate\Database\Seeder;

class CateringPackageSeeder extends Seeder
{
    public function run(): void
    {
        $pakets = [
            ['nama_paket' => 'Paket Nasi Box Ayam', 'deskripsi' => 'Nasi, ayam goreng, sayur, sambal, kerupuk', 'harga_porsi' => 25000, 'min_porsi' => 20],
            ['nama_paket' => 'Paket Prasmanan', 'deskripsi' => 'Nasi, 3 lauk, 2 sayur, buah, es teh', 'harga_porsi' => 45000, 'min_porsi' => 50],
            ['nama_paket' => 'Paket Snack Box', 'deskripsi' => 'Risoles, kue basah, air mineral', 'harga_porsi' => 15000, 'min_porsi' => 30],
        ];

        foreach ($pakets as $paket) {
            CateringPackage::create($paket + ['status_aktif' => 'aktif']);
        }
    }
}
