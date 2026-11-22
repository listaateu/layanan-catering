<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\CateringPackage;
use App\Models\Order;
use App\Models\Payment;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $totalPaket = CateringPackage::count();
        $totalPesanan = Order::count();
        $pembayaranMenunggu = Payment::where('status_verifikasi', 'menunggu')->count();
        $pesananBaru = Order::with('customer')->latest()->take(5)->get();

        return view('admin.pages.dashboard', compact('user', 'totalPaket', 'totalPesanan', 'pembayaranMenunggu', 'pesananBaru'));
    }
}
