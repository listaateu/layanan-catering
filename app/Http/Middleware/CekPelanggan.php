<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CekPelanggan
{
    public function handle(Request $request, Closure $next): Response
    {
        // guest() = sama kayak redirect biasa ke login, tapi halaman yang tadi mau dibuka diingat
        // jadi habis login pelanggan langsung dibawa balik ke halaman itu (dipakai intended() di AuthController)
        if (!Auth::check()) {
            return redirect()->guest(route('login'))->with('error', 'Silahkan login terlebih dahulu');
        }

        if (Auth::user()->role !== 'pelanggan') {
            abort(403, 'Anda tidak memiliki akses ke halaman ini!');
        }

        return $next($request);
    }
}
