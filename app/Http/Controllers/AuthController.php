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
    //index -> tampilin halaman login
    public function index()
    {
        return view('login');
    }

    //authenticate -> proses login
    public function authenticate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ], [
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak benar',
            'password.required' => 'Password wajib diisi'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator->errors());
        }

        if (Auth::attempt($request->only(['email', 'password']))) {
            $request->session()->regenerate();

            // admin kasir ke dashboard admin
            if (Auth::user()->role === 'admin_kasir') {
                return redirect()->route('admin.dashboard')->with(['success' => 'Login berhasil. Selamat Datang, ' . Auth::user()->nama]);
            }

            // kepala dapur ke dashboard dapur
            if (Auth::user()->role === 'kepala_dapur') {
                return redirect()->route('dapur.dashboard')->with(['success' => 'Login berhasil. Selamat Datang, ' . Auth::user()->nama]);
            }

            // intended() = balik ke halaman yang tadi mau dibuka sebelum disuruh login (misal form pesan)
            // kalau ga ada halaman yang diingat, ya ke beranda
            if (Auth::user()->role === 'pelanggan') {
                return redirect()->intended(route('pelanggan.beranda'))->with(['success' => 'Login berhasil. Selamat Datang, ' . Auth::user()->nama]);
            }

            // role lain dikeluarin lagi biar ga error
            Auth::logout();

            return redirect()->route('login')->with('error', 'Halaman untuk akun ini belum tersedia');
        }

        return redirect()->back()->with('error', 'Email / Password yang anda masukkan salah. Silahkan coba lagi!');
    }

    //register -> tampilin halaman daftar
    public function register()
    {
        return view('register');
    }

    //registerStore -> simpan akun baru
    public function registerStore(Request $request)
    {
        $request->validate([
            'nama'          => 'required|string|max:255',
            'email'         => 'required|email|unique:users,email',
            'password'      => 'required|min:8|confirmed',
            'nomor_telepon' => 'required|numeric|digits_between:8,20',
            'alamat'        => 'required|string',
        ]);

        // role langsung pelanggan, jadi lewat form ini ga bisa daftar jadi admin
        User::create([
            'nama'          => $request->nama,
            'email'         => $request->email,
            'password'      => Hash::make($request->password),
            'nomor_telepon' => $request->nomor_telepon,
            'alamat'        => $request->alamat,
            'role'          => 'pelanggan',
        ]);

        return redirect()->route('login')->with('success', 'Akun berhasil dibuat, silakan login');
    }

    //logout -> keluar dari akun
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
