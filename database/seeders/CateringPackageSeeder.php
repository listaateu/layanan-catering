<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CateringPackage;

class CateringPackageSeeder extends Seeder
{
    public function run(): void
    {
        CateringPackage::create([
            'nama_paket' => 'Paket Nasi Box Ayam',
            'deskripsi' => 'Nasi, ayam goreng, sayur, sambal, kerupuk',
            'harga_porsi' => 25000,
            'min_porsi' => 20,
            'status_aktif' => 'aktif'
        ]);
        CateringPackage::create([
            'nama_paket' => 'Paket Prasmanan',
            'deskripsi' => 'Nasi, 3 lauk, 2 sayur, buah, es teh',
            'harga_porsi' => 45000,
            'min_porsi' => 50,
            'status_aktif' => 'aktif'
        ]);
        CateringPackage::create([
            'nama_paket' => 'Paket Snack Box',
            'deskripsi' => 'Risoles, kue basah, air mineral',
            'harga_porsi' => 15000,
            'min_porsi' => 30,
            'status_aktif' => 'aktif'
        ]);
    }
}
