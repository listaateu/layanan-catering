<?php

namespace App\Http\Controllers\pelanggan;

use App\Http\Controllers\Controller;
use App\Models\CateringPackage;

class BerandaController extends Controller
{
    //index -> tampilin beranda (3 paket terbaru)
    public function index()
    {
        $paket = CateringPackage::where('status_aktif', 'aktif')->latest()->take(3)->get();

        return view('pelanggan.pages.beranda', compact('paket'));
    }

    //menu -> tampilin semua paket yang aktif
    public function menu()
    {
        $paket = CateringPackage::where('status_aktif', 'aktif')->latest()->get();

        return view('pelanggan.pages.menu', compact('paket'));
    }

    //show -> tampilin detail satu paket
    public function show($id)
    {
        $paket = CateringPackage::find($id);

        // paket ga ada atau sudah nonaktif, balik ke daftar menu
        if (!$paket || $paket->status_aktif != 'aktif') {
            return redirect()->route('pelanggan.menu')->with('error', 'Paket tidak ditemukan');
        }

        return view('pelanggan.pages.detail', compact('paket'));
    }

    //tentang -> tampilin halaman tentang kami
    public function tentang()
    {
        return view('pelanggan.pages.tentang');
    }

    //kontak -> tampilin halaman kontak
    public function kontak()
    {
        return view('pelanggan.pages.kontak');
    }
}
