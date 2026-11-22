<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\CateringPackage;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PaketController extends Controller
{
    public function index()
    {
        $paket = CateringPackage::latest()->get();
        return view('admin.pages.paket.paket', compact('paket'));
    }

    public function create()
    {
        return view('admin.pages.paket.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_paket'   => 'required|string|max:255',
            'deskripsi'    => 'nullable|string',
            'harga_porsi'  => 'required|numeric|min:0',
            'min_porsi'    => 'required|integer|min:1',
            'gambar_paket' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'status_aktif' => 'required|in:aktif,nonaktif',
        ]);

        if ($request->hasFile('gambar_paket')) {
            $data['gambar_paket'] = $request->file('gambar_paket')->store('paket', 'public');
        }

        CateringPackage::create($data);

        return redirect()->route('admin.paket.index')->with('success', 'Paket menu baru berhasil ditambahkan');
    }

    public function edit(CateringPackage $paket)
    {
        return view('admin.pages.paket.update', compact('paket'));
    }

    public function update(Request $request, CateringPackage $paket)
    {
        $data = $request->validate([
            'nama_paket'   => 'required|string|max:255',
            'deskripsi'    => 'nullable|string',
            'harga_porsi'  => 'required|numeric|min:0',
            'min_porsi'    => 'required|integer|min:1',
            'gambar_paket' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'status_aktif' => 'required|in:aktif,nonaktif',
        ]);

        if ($request->hasFile('gambar_paket')) {
            // hapus gambar lama kalau ada
            if ($paket->gambar_paket) {
                Storage::disk('public')->delete($paket->gambar_paket);
            }
            $data['gambar_paket'] = $request->file('gambar_paket')->store('paket', 'public');
        } else {
            // tidak upload baru -> pertahankan gambar lama
            unset($data['gambar_paket']);
        }

        $paket->update($data);

        return redirect()->route('admin.paket.index')->with('success', 'Paket menu berhasil diubah');
    }

    public function destroy(CateringPackage $paket)
    {
        try {
            $paket->delete();
        } catch (QueryException $e) {
            return redirect()->route('admin.paket.index')
                ->with('error', 'Paket tidak bisa dihapus karena sudah dipakai di pesanan');
        }

        if ($paket->gambar_paket) {
            Storage::disk('public')->delete($paket->gambar_paket);
        }

        return redirect()->route('admin.paket.index')->with('success', 'Paket menu berhasil dihapus');
    }
}
