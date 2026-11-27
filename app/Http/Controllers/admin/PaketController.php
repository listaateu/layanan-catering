<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\CateringPackage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PaketController extends Controller
{
    //index -> tampilin daftar paket
    public function index()
    {
        $paket = CateringPackage::latest()->get();

        return view('admin.pages.paket.paket', compact('paket'));
    }

    //create -> tampilin form tambah paket
    public function create()
    {
        return view('admin.pages.paket.create');
    }

    //store -> simpan paket baru
    public function store(Request $request)
    {
        $request->validate([
            'nama_paket'   => 'required|string|max:255',
            'deskripsi'    => 'nullable|string',
            'harga_porsi'  => 'required|numeric|min:1',
            'min_porsi'    => 'required|integer|min:1',
            'gambar_paket' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'status_aktif' => 'required|in:aktif,nonaktif',
        ]);

        // gambar boleh kosong
        $gambar = null;

        if ($request->hasFile('gambar_paket')) {
            $slug = Str::slug($request->nama_paket, '-');
            $filename = $slug . '-' . time() . '.' . $request->file('gambar_paket')->getClientOriginalExtension();
            $gambar = $request->file('gambar_paket')->storeAs('paket', $filename, 'public');
        }

        CateringPackage::create([
            'nama_paket'   => $request->nama_paket,
            'deskripsi'    => $request->deskripsi,
            'harga_porsi'  => $request->harga_porsi,
            'min_porsi'    => $request->min_porsi,
            'gambar_paket' => $gambar,
            'status_aktif' => $request->status_aktif,
        ]);

        return redirect()->route('admin.paket.table')->with('success', 'Paket menu baru berhasil ditambahkan');
    }

    //edit -> tampilin form ubah paket (sudah keisi data lama)
    public function edit($id)
    {
        $paket = CateringPackage::find($id);

        return view('admin.pages.paket.update', compact('paket'));
    }

    //update -> simpan perubahan paket
    public function update(Request $request, CateringPackage $paket)
    {
        $request->validate([
            'nama_paket'   => 'required|string|max:255',
            'deskripsi'    => 'nullable|string',
            'harga_porsi'  => 'required|numeric|min:1',
            'min_porsi'    => 'required|integer|min:1',
            'gambar_paket' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'status_aktif' => 'required|in:aktif,nonaktif',
        ]);

        // kalau ga upload gambar baru, pakai gambar lama
        $gambar = $paket->gambar_paket;

        if ($request->hasFile('gambar_paket')) {
            $slug = Str::slug($request->nama_paket, '-');
            $filename = $slug . '-' . time() . '.' . $request->file('gambar_paket')->getClientOriginalExtension();
            $gambar = $request->file('gambar_paket')->storeAs('paket', $filename, 'public');
        }

        $paket->update([
            'nama_paket'   => $request->nama_paket,
            'deskripsi'    => $request->deskripsi,
            'harga_porsi'  => $request->harga_porsi,
            'min_porsi'    => $request->min_porsi,
            'gambar_paket' => $gambar,
            'status_aktif' => $request->status_aktif,
        ]);

        return redirect()->route('admin.paket.table')->with('success', 'Paket menu berhasil diubah');
    }

    //destroy -> hapus paket
    public function destroy(CateringPackage $paket)
    {
        // paket yang sudah pernah dipesan ga boleh dihapus
        if ($paket->orderItems->count() > 0) {
            return redirect()->route('admin.paket.table')->with('error', 'Paket tidak bisa dihapus karena sudah dipakai di pesanan');
        }

        $paket->delete();

        return redirect()->route('admin.paket.table')->with('success', 'Paket menu berhasil dihapus');
    }
}
