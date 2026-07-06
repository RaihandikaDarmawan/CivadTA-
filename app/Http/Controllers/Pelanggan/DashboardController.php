<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Models\Book;

class DashboardController extends Controller
{
    public function dashboard()
    {
        if (session('role') !== 'pelanggan') return redirect('/')->with('error', 'Akses ditolak!');
        return view('pelanggan.dashboard', ['dummyBooks' => Book::all()]);
    }

    public function beranda()
    {
        return view('pelanggan.beranda');
    }
}
