<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

// middleware satpam yang mengecek setiap orang sebelum masuk ke halaman pesan milik pelanggan
// (katalog menu boleh dibuka siapa saja, jadi tidak memakai satpam ini)
class CekPelanggan
{
    public function handle(Request $request, Closure $next): Response
    {
        // belum login -> arahkan ke halaman login
        // guest() = sekalian mengingat halaman yang tadi mau dibuka,
        // jadi setelah login pelanggan langsung dibawa kembali ke halaman itu
        if (!Auth::check()) {
            return redirect()->guest(route('login'))->with('error', 'Silahkan login terlebih dahulu');
        }

        // sudah login tapi bukan pelanggan -> tolak (403 = dilarang masuk)
        if (Auth::user()->role !== 'pelanggan') {
            abort(403, 'Anda tidak memiliki akses ke halaman ini!');
        }

        // lolos semua pengecekan -> silakan lanjut ke halaman yang dituju
        return $next($request);
    }
}
