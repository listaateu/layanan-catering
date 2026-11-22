<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'nama' => 'Admin Kasir',
            'email' => 'kasir@catering.com',
            'password' => Hash::make('listaateu061208'),
            'nomor_telepon' => '081234567890',
            'alamat' => 'Kantor Catering',
            'role' => 'admin_kasir',
        ]);

        User::create([
            'nama' => 'Kepala Dapur',
            'email' => 'dapur@catering.com',
            'password' => Hash::make('kepaladapur123'),
            'nomor_telepon' => '081234567891',
            'alamat' => 'Dapur Catering',
            'role' => 'kepala_dapur',
        ]);

        User::create([
            'nama' => 'Pelanggan Contoh',
            'email' => 'pelanggan@catering.com',
            'password' => Hash::make('listaateuambarianti061208'),
            'nomor_telepon' => '081234567892',
            'alamat' => 'Jl. Contoh No. 1',
            'role' => 'pelanggan',
        ]);
    }
}
