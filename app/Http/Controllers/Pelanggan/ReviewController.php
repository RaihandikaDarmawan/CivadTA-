<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Review;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        if (session('role') !== 'pelanggan') return redirect('/')->with('error', 'Akses ditolak!');
        
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|min:5|max:1000',
        ], [
            'comment.min' => 'Komentar ulasan minimal harus 5 karakter.',
        ]);

        $order = Order::where('id', $request->order_id)
                      ->where('user_id', Auth::id())
                      ->where('status', 'Selesai')
                      ->firstOrFail();

        // Check if already reviewed
        if ($order->review) {
            return redirect()->back()->with('error', 'Ulasan untuk pesanan ini sudah diisi.');
        }

        Review::create([
            'order_id' => $order->id,
            'user_id' => Auth::id(),
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        // Notify Admin
        Notification::send('admin', 'Ulasan Baru untuk Pesanan #' . $order->order_number, 'Pelanggan ' . Auth::user()->name . ' memberikan rating ' . $request->rating . ' bintang.', null, 'success', '/admin/manajemen-ulasan');

        return redirect()->back()->with('success', 'Ulasan berhasil dikirim!')->with('title', 'Ulasan Berhasil');
    }
}
