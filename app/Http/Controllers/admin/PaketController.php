<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\CateringPackage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PaketController extends Controller
{
    // index -> menampilkan halaman utama (daftar semua paket)
    public function index()
    {
        // latest() = data yang paling baru ada di paling atas
        // get() = ambil semua datanya dari tabel
        $paket = CateringPackage::latest()->get();

        // compact('paket') = kirim variabel $paket ke halaman supaya bisa ditampilkan di tabel
        return view('admin.pages.paket.paket', compact('paket'));
    }

    // create -> menampilkan halaman tambah (form masih kosong)
    public function create()
    {
        return view('admin.pages.paket.create');
    }

    // store -> menjalankan fungsi tambah data (dipanggil saat tombol Simpan ditekan)
    public function store(Request $request)
    {
        // validasi = cek isian form dulu, kalau ada yang salah otomatis kembali ke form + pesan error
        // required = wajib diisi | nullable = boleh kosong | string = harus teks | max = maksimal karakter
        // numeric = harus angka | integer = harus bilangan bulat | min:1 = minimal 1
        // image = harus gambar | mimes = jenis file yang boleh | in = hanya boleh salah satu isi ini
        $request->validate([
            'nama_paket'   => 'required|string|max:255',
            'deskripsi'    => 'nullable|string',
            'harga_porsi'  => 'required|numeric|min:1',
            'min_porsi'    => 'required|integer|min:1',
            'gambar_paket' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'status_aktif' => 'required|in:aktif,nonaktif',
        ]);

        // gambar boleh kosong, jadi awalnya diisi null dulu
        $gambar = null;

        // hasFile = cek apakah ada file gambar yang diupload
        if ($request->hasFile('gambar_paket')) {
            // Str::slug = ubah nama paket jadi tulisan url, contoh: "Paket Nasi Box" jadi "paket-nasi-box"
            // time() = angka waktu sekarang, supaya nama file tidak bentrok kalau ada nama paket yang sama
            $slug = Str::slug($request->nama_paket, '-');
            $filename = $slug . '-' . time() . '.' . $request->file('gambar_paket')->getClientOriginalExtension();

            // storeAs = simpan file ke folder storage/app/public/paket dengan nama yang kita tentukan sendiri
            // yang disimpan di database hanya lokasinya saja, contoh: paket/paket-nasi-box-1732420000.jpg
            $gambar = $request->file('gambar_paket')->storeAs('paket', $filename, 'public');
        }

        // create = simpan data baru ke tabel catering_packages
        CateringPackage::create([
            'nama_paket'   => $request->nama_paket,
            'deskripsi'    => $request->deskripsi,
            'harga_porsi'  => $request->harga_porsi,
            'min_porsi'    => $request->min_porsi,
            'gambar_paket' => $gambar,
            'status_aktif' => $request->status_aktif,
        ]);

        // redirect = pindah ke halaman daftar paket | with = membawa pesan sukses untuk ditampilkan
        return redirect()->route('admin.paket.table')->with('success', 'Paket menu baru berhasil ditambahkan');
    }

    // edit -> menampilkan halaman update (form sudah terisi data lama)
    public function edit($id)
    {
        // find = cari 1 data berdasarkan id
        $paket = CateringPackage::find($id);
        return view('admin.pages.paket.update', compact('paket'));
    }

    // update -> menjalankan fungsi edit data (dipanggil saat tombol Simpan Perubahan ditekan)
    // CateringPackage $paket = Laravel otomatis mencarikan paket sesuai id yang ada di alamat URL
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

        // kalau tidak upload gambar baru, tetap pakai gambar yang lama
        $gambar = $paket->gambar_paket;

        // kalau upload gambar baru, simpan dengan cara yang sama seperti di store
        if ($request->hasFile('gambar_paket')) {
            $slug = Str::slug($request->nama_paket, '-');
            $filename = $slug . '-' . time() . '.' . $request->file('gambar_paket')->getClientOriginalExtension();
            $gambar = $request->file('gambar_paket')->storeAs('paket', $filename, 'public');
        }

        // update = ubah data yang sudah ada di database
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

    // destroy -> menghapus data
    public function destroy(CateringPackage $paket)
    {
        // paket yang sudah pernah dipesan tidak boleh dihapus (kalau dipaksa akan error di database)
        // orderItems = hubungan di model, isinya semua item pesanan yang memakai paket ini
        if ($paket->orderItems->count() > 0) {
            return redirect()->route('admin.paket.table')->with('error', 'Paket tidak bisa dihapus karena sudah dipakai di pesanan');
        }

        // delete = hapus data dari database
        $paket->delete();

        return redirect()->route('admin.paket.table')->with('success', 'Paket menu berhasil dihapus');
    }
}
