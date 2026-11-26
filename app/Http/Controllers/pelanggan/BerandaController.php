<?php

namespace App\Http\Controllers\pelanggan;

use App\Http\Controllers\Controller;
use App\Models\CateringPackage;

// controller untuk halaman yang boleh dibuka siapa saja (tanpa login)
class BerandaController extends Controller
{
    // index -> menampilkan halaman beranda
    public function index()
    {
        // where() = menyaring data, di sini hanya paket yang statusnya aktif
        // latest() = yang paling baru di atas, take(3) = ambil 3 saja
        $paket = CateringPackage::where('status_aktif', 'aktif')->latest()->take(3)->get();

        return view('pelanggan.pages.beranda', compact('paket'));
    }

    // menu -> menampilkan semua paket menu (katalog)
    public function menu()
    {
        // pelanggan hanya boleh melihat paket yang aktif
        $paket = CateringPackage::where('status_aktif', 'aktif')->latest()->get();

        return view('pelanggan.pages.menu', compact('paket'));
    }

    // show -> menampilkan detail satu paket (isi paket, deskripsi, harga, minimal porsi)
    public function show($id)
    {
        // find = cari 1 paket berdasarkan id
        $paket = CateringPackage::find($id);

        // kalau paketnya tidak ada atau sudah dinonaktifkan, kembali ke daftar menu
        if (!$paket || $paket->status_aktif != 'aktif') {
            return redirect()->route('pelanggan.menu')->with('error', 'Paket tidak ditemukan');
        }

        return view('pelanggan.pages.detail', compact('paket'));
    }

    // tentang -> menampilkan halaman tentang kami (isinya tulisan biasa)
    public function tentang()
    {
        return view('pelanggan.pages.tentang');
    }

    // kontak -> menampilkan halaman kontak (isinya tulisan biasa)
    public function kontak()
    {
        return view('pelanggan.pages.kontak');
    }
}
