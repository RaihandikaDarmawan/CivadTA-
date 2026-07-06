<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ReturnRequest;
use App\Models\Notification;

class ReturnRequestController extends Controller
{
    public function index()
    {
        if (session('role') !== 'admin') return redirect('/')->with('error', 'Akses ditolak!');
        
        $returns = ReturnRequest::with(['order', 'user'])->orderBy('created_at', 'desc')->get();
        
        return view('admin.pengembalian', ['returns' => $returns]);
    }

    public function updateStatus(Request $request)
    {
        if (session('role') !== 'admin') return redirect('/')->with('error', 'Akses ditolak!');
        
        $request->validate([
            'id' => 'required|exists:return_requests,id',
            'status' => 'required|in:Disetujui,Ditolak',
            'admin_notes' => 'nullable|string|min:5',
        ], [
            'admin_notes.min' => 'Catatan admin minimal harus 5 karakter.',
        ]);
        
        $returnRequest = ReturnRequest::findOrFail($request->id);
        $returnRequest->status = $request->status;
        $returnRequest->admin_notes = $request->admin_notes;
        $returnRequest->save();
        
        $order = $returnRequest->order;
        
        if ($request->status === 'Disetujui') {
            $order->status = 'Dikembalikan'; // Update order status to 'Dikembalikan'
            $order->save();
            
            // Notify Customer
            Notification::send('pelanggan', 'Pengembalian Disetujui', 'Pengajuan pengembalian untuk Pesanan #' . $order->order_number . ' telah DISETUJUI.', $order->user_id, 'success', '/pelanggan/riwayat');
        } else {
            $order->status = 'Pengembalian Ditolak'; // Update order status to 'Pengembalian Ditolak'
            $order->save();
            
            // Notify Customer
            Notification::send('pelanggan', 'Pengembalian Ditolak', 'Pengajuan pengembalian untuk Pesanan #' . $order->order_number . ' telah DITOLAK: ' . ($request->admin_notes ?? 'Tidak ada catatan tambahan.'), $order->user_id, 'warning', '/pelanggan/riwayat');
        }
        
        return redirect()->back()->with('success', 'Status pengembalian untuk Pesanan #' . $order->order_number . ' berhasil diubah menjadi ' . $request->status);
    }
}
