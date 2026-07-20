<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderMessage;
use App\Models\Notification;
use App\Models\Admin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ChatController extends Controller
{
    public function customerChat($order_id)
    {
        if (session('role') !== 'pelanggan') return redirect('/')->with('error', 'Akses ditolak!');
        $order = Order::where('id', $order_id)->where('user_id', Auth::id())->firstOrFail();
        
        if ($order->status === 'Selesai' || $order->status === 'Dibatalkan' || $order->status === 'Dikembalikan') {
            return redirect('/pelanggan/riwayat')->with('error', 'Chat tidak tersedia untuk pesanan yang telah selesai, dibatalkan, atau dikembalikan.');
        }
        
        // Mark all admin messages for this order as read
        OrderMessage::where('order_id', $order->id)
                    ->where('sender_type', 'admin')
                    ->update(['is_read' => true]);

        return view('pelanggan.chat', ['order' => $order]);
    }

    public function customerSend(Request $request, $order_id)
    {
        if (session('role') !== 'pelanggan') return response()->json(['error' => 'Akses ditolak!'], 403);
        $order = Order::where('id', $order_id)->where('user_id', Auth::id())->firstOrFail();
        
        if ($order->status === 'Selesai' || $order->status === 'Dibatalkan' || $order->status === 'Dikembalikan') {
            return response()->json(['error' => 'Chat tidak tersedia untuk pesanan yang telah selesai, dibatalkan, atau dikembalikan.'], 403);
        }
        
        $request->validate([
            'message' => 'nullable|required_without:image|string|min:2|max:2000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ], [
            'image.image' => 'File harus berupa gambar.',
            'image.mimes' => 'Format gambar harus jpeg, png, jpg, gif, atau webp.',
            'image.max' => 'Ukuran gambar maksimal adalah 2 MB.',
            'message.required_without' => 'Pesan atau gambar harus diisi.',
            'message.min' => 'Pesan minimal harus 2 karakter.',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('chats', 'public');
            $imagePath = '/storage/' . $path;
        }

        $msg = OrderMessage::create([
            'order_id' => $order->id,
            'sender_type' => 'pelanggan',
            'sender_id' => Auth::id(),
            'message' => $request->message,
            'image' => $imagePath,
            'is_read' => false
        ]);

        // Send a Notification to admin
        $notifText = $request->message ? Str::limit($request->message, 50) : 'Mengirim sebuah gambar';
        Notification::send('admin', 'Pesan Baru Pelanggan #' . $order->order_number, 'Pesan: ' . $notifText, null, 'info', '/admin/chat/' . $order->id);

        return response()->json([
            'success' => true,
            'message' => $msg
        ]);
    }

    public function adminChat($order_id)
    {
        if (session('role') !== 'admin') return redirect('/')->with('error', 'Akses ditolak!');
        $order = Order::findOrFail($order_id);

        if ($order->status === 'Selesai' || $order->status === 'Dibatalkan' || $order->status === 'Dikembalikan') {
            return redirect('/admin/manajemen-pesanan')->with('error', 'Chat tidak tersedia untuk pesanan yang telah selesai, dibatalkan, atau dikembalikan.');
        }

        // Mark all customer messages for this order as read
        OrderMessage::where('order_id', $order->id)
                    ->where('sender_type', 'pelanggan')
                    ->update(['is_read' => true]);

        return view('admin.chat', ['order' => $order]);
    }

    public function adminSend(Request $request, $order_id)
    {
        if (session('role') !== 'admin') return response()->json(['error' => 'Akses ditolak!'], 403);
        $order = Order::findOrFail($order_id);
        
        if ($order->status === 'Selesai' || $order->status === 'Dibatalkan' || $order->status === 'Dikembalikan') {
            return response()->json(['error' => 'Chat tidak tersedia untuk pesanan yang telah selesai, dibatalkan, atau dikembalikan.'], 403);
        }
        
        $request->validate([
            'message' => 'nullable|required_without:image|string|min:2|max:2000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ], [
            'image.image' => 'File harus berupa gambar.',
            'image.mimes' => 'Format gambar harus jpeg, png, jpg, gif, atau webp.',
            'image.max' => 'Ukuran gambar maksimal adalah 2 MB.',
            'message.required_without' => 'Pesan atau gambar harus diisi.',
            'message.min' => 'Pesan minimal harus 2 karakter.',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('chats', 'public');
            $imagePath = '/storage/' . $path;
        }

        $adminId = session('admin_id') ?? Admin::where('name', session('username'))->first()->id;

        $msg = OrderMessage::create([
            'order_id' => $order->id,
            'sender_type' => 'admin',
            'sender_id' => $adminId,
            'message' => $request->message,
            'image' => $imagePath,
            'is_read' => false
        ]);

        // Send a Notification to customer
        $notifText = $request->message ? Str::limit($request->message, 50) : 'Mengirim sebuah gambar';
        Notification::send('pelanggan', 'Pesan Baru dari Admin CIVAD', 'Admin: ' . $notifText, $order->user_id, 'info', '/pelanggan/chat/' . $order->id);

        return response()->json([
            'success' => true,
            'message' => $msg
        ]);
    }

    public function getMessages($order_id)
    {
        $role = session('role');
        if ($role === 'pelanggan') {
            $order = Order::where('id', $order_id)->where('user_id', Auth::id())->first();
            if (!$order) return response()->json(['error' => 'Akses ditolak!'], 403);
            
            if ($order->status === 'Selesai' || $order->status === 'Dibatalkan' || $order->status === 'Dikembalikan') {
                return response()->json(['error' => 'Chat tidak tersedia untuk pesanan yang telah selesai, dibatalkan, atau dikembalikan.'], 403);
            }
            
            // Mark all admin messages for this order as read
            OrderMessage::where('order_id', $order->id)
                        ->where('sender_type', 'admin')
                        ->update(['is_read' => true]);

        } elseif ($role === 'admin') {
            $order = Order::find($order_id);
            if (!$order) return response()->json(['error' => 'Not Found'], 404);

            if ($order->status === 'Selesai' || $order->status === 'Dibatalkan' || $order->status === 'Dikembalikan') {
                return response()->json(['error' => 'Chat tidak tersedia untuk pesanan yang telah selesai, dibatalkan, atau dikembalikan.'], 403);
            }

            // Mark all customer messages for this order as read
            OrderMessage::where('order_id', $order->id)
                        ->where('sender_type', 'pelanggan')
                        ->update(['is_read' => true]);
        } else {
            return response()->json(['error' => 'Akses ditolak!'], 403);
        }

        $messages = OrderMessage::where('order_id', $order_id)
                                ->orderBy('created_at', 'asc')
                                ->get()
                                ->map(function ($msg) {
                                    return [
                                        'id' => $msg->id,
                                        'sender_type' => $msg->sender_type,
                                        'message' => $msg->message,
                                        'image' => $msg->image,
                                        'time' => $msg->created_at->format('H:i'),
                                    ];
                                });

        return response()->json(['messages' => $messages]);
    }
}
