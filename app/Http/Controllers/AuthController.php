<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    // index -> menampilkan halaman login
    public function index()
    {
        return view('login');
    }

    // authenticate -> memproses login (dipanggil saat tombol Login ditekan)
    public function authenticate(Request $request)
    {
        // validasi email dan password
        // saat login password cukup dicek diisi atau tidak (aturan min:8 dipasang waktu daftar saja)
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ], [
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak benar',
            'password.required' => 'Password wajib diisi'
        ]);

        // kalau validasi gagal, balik ke halaman login bawa pesan error dan email yang tadi diketik
        // withInput() = mengisi kembali kolom email supaya tidak perlu ketik ulang
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator->errors());
        }

        // Auth::attempt() = cek email dan password cocok dengan data di database atau tidak
        // kalau cocok, user otomatis dianggap sudah login
        if (Auth::attempt($request->only(['email', 'password']))) {
            // buat session baru supaya lebih aman
            $request->session()->regenerate();

            // cek role admin kasir
            if (Auth::user()->role === 'admin_kasir') {
                return redirect()->route('admin.dashboard')->with(['success' => 'Login berhasil. Selamat Datang, ' . Auth::user()->nama]);
            }

            // cek role kepala dapur
            if (Auth::user()->role === 'kepala_dapur') {
                return redirect()->route('dapur.dashboard')->with(['success' => 'Login berhasil. Selamat Datang, ' . Auth::user()->nama]);
            }

            // cek role pelanggan
            // intended() = kembali ke halaman yang tadi mau dibuka sebelum disuruh login (contoh: form pesan)
            // kalau tidak ada, pelanggan dibawa ke beranda
            if (Auth::user()->role === 'pelanggan') {
                return redirect()->intended(route('pelanggan.beranda'))->with(['success' => 'Login berhasil. Selamat Datang, ' . Auth::user()->nama]);
            }

            // role yang tidak dikenal dikeluarkan lagi supaya tidak error 500
            Auth::logout();

            return redirect()->route('login')->with('error', 'Halaman untuk akun ini belum tersedia');
        }

        // kembali ke login kalau email atau password salah
        return redirect()->back()->with('error', 'Email / Password yang anda masukkan salah. Silahkan coba lagi!');
    }

    // register -> menampilkan halaman daftar akun baru
    public function register()
    {
        return view('register');
    }

    // registerStore -> menyimpan akun baru (dipanggil saat tombol Daftar ditekan)
    public function registerStore(Request $request)
    {
        // validasi inputan dari form
        // unique:users,email = email belum boleh dipakai akun lain
        // confirmed = isi password harus sama dengan kolom password_confirmation
        $request->validate([
            'nama'          => 'required|string|max:255',
            'email'         => 'required|email|unique:users,email',
            'password'      => 'required|min:8|confirmed',
            'nomor_telepon' => 'required|numeric|digits_between:8,20',
            'alamat'        => 'required|string',
        ]);

        // create() = simpan akun baru ke tabel users
        // Hash::make() = password diacak dulu, tidak disimpan apa adanya
        // role otomatis pelanggan, jadi tidak ada yang bisa mendaftar sebagai admin lewat form ini
        User::create([
            'nama'          => $request->nama,
            'email'         => $request->email,
            'password'      => Hash::make($request->password),
            'nomor_telepon' => $request->nomor_telepon,
            'alamat'        => $request->alamat,
            'role'          => 'pelanggan',
        ]);

        // setelah daftar, pelanggan diminta login
        return redirect()->route('login')->with('success', 'Akun berhasil dibuat, silakan login');
    }

    // logout -> keluar dari akun (dipanggil lewat form POST dari tombol logout)
    public function logout(Request $request)
    {
        // Auth::logout() = user tidak lagi dianggap login
        Auth::logout();

        // hapus session lama dan buat token csrf baru
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
