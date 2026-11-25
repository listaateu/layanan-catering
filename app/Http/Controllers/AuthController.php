<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
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

            // role lain (pelanggan) halamannya belum dibuat
            // jadi dikeluarkan lagi supaya tidak error 500
            Auth::logout();

            return redirect()->route('login')->with('error', 'Halaman untuk akun ini belum tersedia');
        }

        // kembali ke login kalau email atau password salah
        return redirect()->back()->with('error', 'Email / Password yang anda masukkan salah. Silahkan coba lagi!');
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
