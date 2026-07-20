<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\ReturnRequest;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class ReturnRequestController extends Controller
{
    public function create(Request $request)
    {
        if (session('role') !== 'pelanggan') return redirect('/')->with('error', 'Akses ditolak!');
        
        $orderId = $request->query('order_id');
        $order = Order::where('id', $orderId)
                      ->where('user_id', Auth::id())
                      ->whereIn('status', ['Dikirim', 'Sedang Dikirim', 'Pesanan Sedang Dikirim'])
                      ->with('items.book')
                      ->firstOrFail();
                      
        // Check if return request already exists
        if ($order->returnRequest) {
            return redirect('/pelanggan/riwayat')->with('error', 'Pengajuan pengembalian untuk pesanan ini sudah dibuat.');
        }
                      
        return view('pelanggan.pengembalian', ['order' => $order]);
    }

    public function store(Request $request)
    {
        if (session('role') !== 'pelanggan') return redirect('/')->with('error', 'Akses ditolak!');
        
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'reason' => 'required|string|min:10',
            'video_proof' => 'required|mimes:mp4,mov,avi,webm|max:51200', // max 50MB
            'bank_name' => 'required|string|min:3|max:255',
            'bank_account_number' => 'required|numeric|digits_between:10,16',
        ], [
            'reason.required' => 'Alasan pengembalian wajib diisi.',
            'reason.min' => 'Alasan pengembalian minimal harus 10 karakter.',
            'video_proof.required' => 'Bukti video wajib diunggah.',
            'video_proof.mimes' => 'Format video harus berupa mp4, mov, avi, atau webm.',
            'video_proof.max' => 'Video gagal diunggah karena ukuran file melebihi 50 MB',
            'bank_name.required' => 'Nama bank wajib diisi.',
            'bank_name.min' => 'Nama bank minimal harus 3 karakter.',
            'bank_account_number.required' => 'Nomor rekening wajib diisi.',
            'bank_account_number.numeric' => 'Nomor rekening harus berupa angka dengan jumlah digit 10-16.',
            'bank_account_number.digits_between' => 'Nomor rekening harus berupa angka dengan jumlah digit 10-16.',
        ]);
        
        $order = Order::where('id', $request->order_id)
                      ->where('user_id', Auth::id())
                      ->whereIn('status', ['Dikirim', 'Sedang Dikirim', 'Pesanan Sedang Dikirim'])
                      ->firstOrFail();
                      
        if ($order->returnRequest) {
            return redirect('/pelanggan/riwayat')->with('error', 'Pengajuan pengembalian untuk pesanan ini sudah dibuat.');
        }
        
        $videoPath = $request->file('video_proof')->store('returns', 'public');
        $videoUrl = '/storage/' . $videoPath;
        
        ReturnRequest::create([
            'order_id' => $order->id,
            'user_id' => Auth::id(),
            'reason' => $request->reason,
            'video_proof' => $videoUrl,
            'status' => 'Pending',
            'bank_name' => $request->bank_name,
            'bank_account_number' => $request->bank_account_number,
        ]);
        
        // Update order status to 'Pengajuan Pending'
        $order->status = 'Pengajuan Pending';
        $order->save();
        
        // Notify Admins
        Notification::send('admin', 'Pengajuan Pengembalian Baru', 'Pelanggan ' . Auth::user()->name . ' mengajukan pengembalian untuk Pesanan #' . $order->order_number, null, 'warning', '/admin/manajemen-pengembalian');
        
        return redirect('/pelanggan/riwayat')->with('success', 'Pengajuan pengembalian berhasil dikirim! Menunggu konfirmasi admin.')->with('title', 'Pengajuan Berhasil');
    }
}
