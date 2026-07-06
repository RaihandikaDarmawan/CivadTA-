<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Book;

class DashboardController extends Controller
{
    public function index()
    {
        if (session('role') !== 'admin') return redirect('/')->with('error', 'Akses ditolak!');
        
        $totalPelanggan = User::where('role', 'pelanggan')->count();
        $totalPesanan = Order::count();
        $totalBukuTerjual = OrderItem::sum('quantity');
        $totalPendapatan = Order::sum('total_amount');
        $totalJenisBuku = Book::count();
        $menungguVerifikasi = Order::whereIn('status', ['Menunggu Verifikasi', 'Pending'])->count();
        
        return view('admin.dashboard', [
            'totalPelanggan' => $totalPelanggan,
            'totalPesanan' => $totalPesanan,
            'totalBukuTerjual' => $totalBukuTerjual,
            'totalPendapatan' => $totalPendapatan,
            'totalJenisBuku' => $totalJenisBuku,
            'menungguVerifikasi' => $menungguVerifikasi
        ]);
    }
}
