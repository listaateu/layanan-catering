<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

// middleware = "satpam" yang mengecek setiap orang sebelum masuk ke halaman admin
class CekAdminKasir
{
    public function handle(Request $request, Closure $next): Response
    {
        // belum login -> kembalikan ke halaman login
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silahkan login terlebih dahulu');
        }

        // sudah login tapi bukan admin kasir -> tolak (403 = dilarang masuk)
        if (Auth::user()->role !== 'admin_kasir') {
            abort(403, 'Anda tidak memiliki akses ke halaman ini!');
        }

        // lolos semua pengecekan -> silakan lanjut ke halaman yang dituju
        return $next($request);
    }
}
