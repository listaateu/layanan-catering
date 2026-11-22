<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function index()
    {
        return view('login');
    }

    public function authenticate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:8|not_in:password,1234'
        ], [
            'required' => 'atribut ini wajib diisi',
            'not_in' => 'atribut ini mengandung kata yang mudah ditebak!'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator->errors());
        }

        if (Auth::attempt($request->only(['email', 'password']))) {
            $request->session()->regenerate();

            if (Auth::user()->role === 'admin_kasir') {
                return redirect()->route('admin.dashboard')->with(['success' => 'Login berhasil. Selamat Datang, ' . Auth::user()->nama]);
            }

            if (Auth::user()->role === 'kepala_dapur') {
                return redirect()->route('dapur.dashboard')->with(['success' => 'Login berhasil. Selamat Datang, ' . Auth::user()->nama]);
            }

            if (Auth::user()->role === 'pelanggan') {
                return redirect()->route('pelanggan.dashboard')->with(['success' => 'Login berhasil. Selamat Datang, ' . Auth::user()->nama]);
            }
        }

        return redirect()->back()->with('error', 'Email / Password yang anda masukkan salah. Silahkan coba lagi!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
