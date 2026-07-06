<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Midtrans\Config;
use App\Models\Order;
use App\Models\Notification;

class MidtransCallbackController extends Controller
{
    public function handle(Request $request)
    {
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);

        $notification = new \Midtrans\Notification();

        $transaction = $notification->transaction_status;
        $order_id = $notification->order_id;
        $fraud = $notification->fraud_status;

        $order = Order::where('order_number', $order_id)->first();

        if ($order) {
            if ($transaction == 'settlement' || ($transaction == 'capture' && $fraud !== 'challenge')) {
                $order->status = 'Pesanan Sedang Dikemas';
                
                // Award points to user if not already awarded
                $user = $order->user;
                if ($user && !$order->points_awarded) {
                    // 1 Point for every 10,000 IDR
                    $pointsEarned = floor($order->total_amount / 10000);
                    $user->increment('points', $pointsEarned);
                    $order->points_awarded = true;

                    // Notify Customer
                    Notification::send('pelanggan', 'Pembayaran Berhasil!', 'Pembayaran untuk pesanan #' . $order->order_number . ' telah kami terima. Anda mendapatkan ' . $pointsEarned . ' poin loyalty!', $user->id, 'success', '/pelanggan/riwayat');
                }
            } else if ($transaction == 'pending') {
                $order->status = 'Pending';
            } else if ($transaction == 'deny') {
                $order->status = 'Cancelled';
            } else if ($transaction == 'expire') {
                $order->status = 'Cancelled';
            } else if ($transaction == 'cancel') {
                $order->status = 'Cancelled';
            }
            $order->save();
        }

        return response()->json(['status' => 'success']);
    }
}
