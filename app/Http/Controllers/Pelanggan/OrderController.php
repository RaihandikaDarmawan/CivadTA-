<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderController extends Controller
{
    private function initMidtrans()
    {
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
        Config::$isSanitized = true;
        Config::$is3ds = true;

        if (env('APP_ENV', 'local') === 'local') {
            Config::$curlOptions = [
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_HTTPHEADER => [
                    'Content-Type: application/json',
                ],
            ];
        }
    }

    public function checkout()
    {
        if (session('role') !== 'pelanggan') return redirect('/')->with('error', 'Akses ditolak!');
        
        if (request()->query('from') === 'cart' || !session()->has('checkout_type')) {
            session(['checkout_type' => 'cart']);
            session(['checkout_items' => session('cart', [])]);
        }
        
        $items = session('checkout_items', []);
        foreach ($items as $id => $item) {
            $book = Book::find($id);
            if (!$book || $item['qty'] > $book->stock) {
                return redirect('/pelanggan/keranjang')->with('error', 'stok tidak mencukupi, tolong ubah jumlah stok');
            }
        }
        return view('pelanggan.pesanan');
    }

    public function confirmPayment(Request $request)
    {
        $cart = session('checkout_items', []);
        if (empty($cart)) return redirect('/pelanggan/dashboard');

        $user = Auth::user();
        if (!$user) return redirect('/login')->with('error', 'Silakan login terlebih dahulu');

        $request->validate([
            'recipient_name' => 'required|string|min:3|max:255',
            'phone_number' => 'required|digits_between:10,13',
            'address' => 'required|string|min:5',
            'distance_km' => 'required|integer|min:1',
            'shipping_service' => 'required|string|in:GoSend Same Day,GoSend Instant',
        ], [
            'recipient_name.required' => 'Nama penerima wajib diisi.',
            'recipient_name.min' => 'Nama penerima minimal harus 3 karakter.',
            'phone_number.required' => 'Nomor handphone wajib diisi.',
            'phone_number.digits_between' => 'nomor telepon harus terdiri dari 10-13 digit',
            'address.required' => 'Alamat lengkap wajib diisi.',
            'address.min' => 'Alamat minimal harus 5 karakter.',
            'shipping_service.required' => 'Opsi pengiriman wajib dipilih.',
        ]);

        // Validate stock before creating order
        foreach ($cart as $item) {
            $book = Book::find($item['id']);
            if (!$book || $item['qty'] > $book->stock) {
                return redirect('/pelanggan/keranjang')->with('error', 'stok tidak mencukupi, tolong ubah jumlah stok');
            }
        }

        $orderNumber = 'ORD-' . strtoupper(Str::random(8));
        $subtotal = collect($cart)->sum(fn($i) => $i['price'] * $i['qty']);
        $distanceKm = (int) $request->input('distance_km', 3);
        $shippingService = $request->input('shipping_service', 'GoSend Same Day');
        
        $shipping = 0;
        if ($distanceKm > 0) {
            if ($shippingService === 'GoSend Same Day') {
                if ($distanceKm <= 3) {
                    $shipping = 12000;
                } elseif ($distanceKm <= 15) {
                    $shipping = 18000;
                } else {
                    $shipping = (int) ($distanceKm * 1200);
                }
            } elseif ($shippingService === 'GoSend Instant') {
                if ($distanceKm <= 20) {
                    $shipping = (int) max(20000, $distanceKm * 2500);
                } else {
                    $shipping = (int) ($distanceKm * 3000);
                }
            } else {
                // fallback / GoSend Same Day
                if ($distanceKm <= 3) {
                    $shipping = 12000;
                } elseif ($distanceKm <= 15) {
                    $shipping = 18000;
                } else {
                    $shipping = (int) ($distanceKm * 1200);
                }
            }
        }
        
        $discount = session('active_discount', 0);
        $pointsToRedeem = (int) $request->input('points_to_redeem', 0);
        $pointsDiscount = 0;
        if ($pointsToRedeem >= 100 && $pointsToRedeem <= $user->points && $pointsToRedeem % 50 === 0) {
            $pointsDiscount = $pointsToRedeem * 100;
        }
        $discount += $pointsDiscount;
        $total = max(0, $subtotal + $shipping - $discount);

        if ($total == 0) {
            $order = null;
            DB::transaction(function () use ($user, $orderNumber, $total, $cart, &$order, $request, $shipping, $pointsToRedeem, $shippingService) {
                $order = Order::create([
                    'user_id' => $user->id,
                    'order_number' => $orderNumber,
                    'total_amount' => $total,
                    'status' => 'Pesanan Sedang Dikemas',
                    'payment_method' => 'Midtrans (Bypass)',
                    'recipient_name' => $request->input('recipient_name'),
                    'phone_number' => $request->input('phone_number'),
                    'address' => $request->input('address'),
                    'latitude' => $request->input('latitude'),
                    'longitude' => $request->input('longitude'),
                    'distance_km' => $request->input('distance_km'),
                    'shipping_cost' => $shipping,
                    'shipping_service' => $shippingService,
                ]);

                if ($pointsToRedeem >= 100 && $pointsToRedeem <= $user->points && $pointsToRedeem % 50 === 0) {
                    $user->decrement('points', $pointsToRedeem);
                }

                foreach ($cart as $item) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'book_id' => $item['id'],
                        'quantity' => $item['qty'],
                        'price' => $item['price'],
                    ]);
                    
                    // Potong stok buku
                    $book = Book::find($item['id']);
                    if ($book) {
                        $book->stock -= $item['qty'];
                        $book->save();
                    }
                }
            });

            if (session('checkout_type', 'cart') === 'cart') {
                session(['cart' => []]);
                session(['cart_count' => 0]);
            }
            session(['active_discount' => 0]);
            session()->forget(['checkout_type', 'checkout_items']);

            // Notify Admins about new order
            Notification::send('admin', 'Pesanan Baru #' . $orderNumber, 'Pelanggan ' . $user->name . ' baru saja melakukan pemesanan sebesar Rp ' . number_format($total, 0, ',', '.'), null, 'info', '/admin/manajemen-pesanan');
            
            // Notify Customer
            Notification::send('pelanggan', 'Pemesanan Berhasil!', 'Pesanan #' . $orderNumber . ' telah kami terima. Status: Pesanan Sedang Dikemas.', $user->id, 'success', '/pelanggan/riwayat');

            return redirect('/pelanggan/riwayat')->with('success', 'Pesanan berhasil dibuat! Pembayaran Rp 0 berhasil diproses.');
        }

        $this->initMidtrans();

        $order = null;
        DB::transaction(function () use ($user, $orderNumber, $total, $cart, &$order, $request, $shipping, $pointsToRedeem, $shippingService) {
            $order = Order::create([
                'user_id' => $user->id,
                'order_number' => $orderNumber,
                'total_amount' => $total,
                'status' => 'Pending',
                'payment_method' => 'Midtrans',
                'recipient_name' => $request->input('recipient_name'),
                'phone_number' => $request->input('phone_number'),
                'address' => $request->input('address'),
                'latitude' => $request->input('latitude'),
                'longitude' => $request->input('longitude'),
                'distance_km' => $request->input('distance_km'),
                'shipping_cost' => $shipping,
                'shipping_service' => $shippingService,
            ]);

            if ($pointsToRedeem >= 100 && $pointsToRedeem <= $user->points && $pointsToRedeem % 50 === 0) {
                $user->decrement('points', $pointsToRedeem);
            }

            foreach ($cart as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'book_id' => $item['id'],
                    'quantity' => $item['qty'],
                    'price' => $item['price'],
                ]);
                
                // Potong stok buku
                $book = Book::find($item['id']);
                if ($book) {
                    $book->stock -= $item['qty'];
                    $book->save();
                }
            }
        });

        // Buat Transaksi Midtrans
        $params = [
            'transaction_details' => [
                'order_id' => $orderNumber,
                'gross_amount' => $total,
            ],
            'customer_details' => [
                'first_name' => $user->name,
                'email' => $user->username . '@example.com', // Dummy email as username is used
            ],
        ];

        try {
            $snapToken = Snap::getSnapToken($params);
            $order->snap_token = $snapToken;
            $order->save();

            if (session('checkout_type', 'cart') === 'cart') {
                session(['cart' => []]);
                session(['cart_count' => 0]);
            }
            
            // Notify Admins about new order
            Notification::send('admin', 'Pesanan Baru #' . $orderNumber, 'Pelanggan ' . $user->name . ' baru saja melakukan pemesanan sebesar Rp ' . number_format($total, 0, ',', '.'), null, 'info', '/admin/manajemen-pesanan');
            session(['active_discount' => 0]);
            session()->forget(['checkout_type', 'checkout_items']);

            return view('pelanggan.pembayaran_midtrans', [
                'order' => $order,
                'snapToken' => $snapToken
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal terhubung ke Midtrans: ' . $e->getMessage());
        }
    }

    public function payment(Request $request)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'recipient_name' => 'required|string|min:3|max:255',
                'phone_number' => 'required|digits_between:10,13',
                'address' => 'required|string|min:5',
                'distance_km' => 'required|integer|min:1',
                'shipping_service' => 'required|string|in:GoSend Same Day,GoSend Instant',
            ], [
                'recipient_name.required' => 'Nama penerima wajib diisi.',
                'recipient_name.min' => 'Nama penerima minimal harus 3 karakter.',
                'phone_number.required' => 'Nomor handphone wajib diisi.',
                'phone_number.digits_between' => 'pastikan nomor anda minimal 10-13 digit',
                'address.required' => 'Alamat lengkap wajib diisi.',
                'address.min' => 'Alamat minimal harus 5 karakter.',
                'shipping_service.required' => 'Opsi pengiriman wajib dipilih.',
                'shipping_service.in' => 'Opsi pengiriman tidak valid.',
            ]);

            // Validate stock backend side
            $cart = session('cart', []);
            foreach ($cart as $id => $item) {
                $book = Book::find($id);
                if (!$book || $item['qty'] > $book->stock) {
                    return redirect('/pelanggan/keranjang')->with('error', 'stok tidak mencukupi, tolong ubah jumlah stok');
                }
            }

            // Calculate total payment
            $subtotal = collect($cart)->sum(fn($i) => $i['price'] * $i['qty']);
            $distanceKm = (int) $request->input('distance_km', 3);
            $shippingService = $request->input('shipping_service', 'GoSend Same Day');

            $shipping = 0;
            if ($distanceKm > 0) {
                if ($shippingService === 'GoSend Same Day') {
                    if ($distanceKm <= 3) {
                        $shipping = 12000;
                    } elseif ($distanceKm <= 15) {
                        $shipping = 18000;
                    } else {
                        $shipping = (int) ($distanceKm * 1200);
                    }
                } elseif ($shippingService === 'GoSend Instant') {
                    if ($distanceKm <= 20) {
                        $shipping = (int) max(20000, $distanceKm * 2500);
                    } else {
                        $shipping = (int) ($distanceKm * 3000);
                    }
                } else {
                    if ($distanceKm <= 3) {
                        $shipping = 12000;
                    } elseif ($distanceKm <= 15) {
                        $shipping = 18000;
                    } else {
                        $shipping = (int) ($distanceKm * 1200);
                    }
                }
            }

            $discount = session('active_discount', 0);
            $pointsToRedeem = (int) $request->input('points_to_redeem', 0);
            $pointsDiscount = 0;
            $user = Auth::user();
            if ($user && $pointsToRedeem >= 100 && $pointsToRedeem <= $user->points && $pointsToRedeem % 50 === 0) {
                $pointsDiscount = $pointsToRedeem * 100;
            }
            $discount += $pointsDiscount;
            $total = max(0, $subtotal + $shipping - $discount);

            if ($total === 0) {
                $orderNumber = 'ORD-' . strtoupper(Str::random(8));
                $order = null;

                DB::transaction(function () use ($user, $orderNumber, $total, $cart, &$order, $request, $shipping, $pointsToRedeem, $shippingService) {
                    $order = Order::create([
                        'user_id' => $user->id,
                        'order_number' => $orderNumber,
                        'total_amount' => $total,
                        'status' => 'Pesanan Sedang Dikemas',
                        'payment_method' => 'Midtrans (Bypass)',
                        'recipient_name' => $request->input('recipient_name'),
                        'phone_number' => $request->input('phone_number'),
                        'address' => $request->input('address'),
                        'latitude' => $request->input('latitude'),
                        'longitude' => $request->input('longitude'),
                        'distance_km' => $request->input('distance_km'),
                        'shipping_cost' => $shipping,
                        'shipping_service' => $shippingService,
                    ]);

                    if ($pointsToRedeem >= 100 && $pointsToRedeem <= $user->points && $pointsToRedeem % 50 === 0) {
                        $user->decrement('points', $pointsToRedeem);
                    }

                    foreach ($cart as $item) {
                        OrderItem::create([
                            'order_id' => $order->id,
                            'book_id' => $item['id'],
                            'quantity' => $item['qty'],
                            'price' => $item['price'],
                        ]);

                        // Potong stok buku
                        $book = Book::find($item['id']);
                        if ($book) {
                            $book->stock -= $item['qty'];
                            $book->save();
                        }
                    }
                });

                session(['cart' => []]);
                session(['cart_count' => 0]);
                session(['active_discount' => 0]);

                // Notify Admins about new order
                Notification::send('admin', 'Pesanan Baru #' . $orderNumber, 'Pelanggan ' . $user->name . ' baru saja melakukan pemesanan sebesar Rp ' . number_format($total, 0, ',', '.'), null, 'info', '/admin/manajemen-pesanan');

                // Notify Customer
                Notification::send('pelanggan', 'Pemesanan Berhasil!', 'Pesanan #' . $orderNumber . ' telah kami terima. Status: Pesanan Sedang Dikemas.', $user->id, 'success', '/pelanggan/riwayat');

                return redirect('/pelanggan/riwayat')->with('success', 'Pesanan berhasil dibuat! Pembayaran Rp 0 berhasil diproses.');
            }
        }
        return view('pelanggan.pembayaran', ['request' => $request]);
    }

    public function history()
    {
        if (session('role') !== 'pelanggan') {
            return redirect('/login')->with('error', 'Silakan login terlebih dahulu.');
        }
        
        Order::autoCompleteOldOrders();
        
        $orders = Order::where('user_id', Auth::id())
                       ->with(['items.book', 'returnRequest', 'review'])
                       ->orderBy('created_at', 'desc')
                       ->get();

        // Local Test Fix: Sync status with Midtrans if still pending
        $this->initMidtrans();

        foreach ($orders as $order) {
            if ($order->status === 'Pending' && $order->payment_method === 'Midtrans') {
                try {
                    $status = Transaction::status($order->order_number);
                    if ($status->transaction_status == 'settlement' || ($status->transaction_status == 'capture' && $status->fraud_status !== 'challenge')) {
                        $order->status = 'Pesanan Sedang Dikemas';
                        
                        // Award points if not already awarded
                        $user = $order->user;
                        if ($user && !$order->points_awarded) {
                            $subtotal = $order->items->sum(fn($item) => $item->price * $item->quantity);
                            $pointsEarned = floor($subtotal / 10000);
                            $user->increment('points', $pointsEarned);
                            $order->points_awarded = true;
                            
                            // Notify Customer
                            Notification::send('pelanggan', 'Pembayaran Berhasil!', 'Pembayaran untuk pesanan #' . $order->order_number . ' telah kami terima. Anda mendapatkan ' . $pointsEarned . ' poin loyalty!', $user->id, 'success', '/pelanggan/riwayat');
                        }
                        $order->save();
                    }
                } catch (\Exception $e) {
                    // Silently skip if order not found in Midtrans yet
                }
            }
        }
        if (request()->query('status') === 'success' || request()->query('status') === 'pending') {
            session()->now('title', 'Pembayaran Berhasil');
            session()->now('success', 'Pesanan akan di proses dan segera dikirim');
        }
        
        return view('pelanggan.riwayat', ['orders' => $orders]);
    }

    public function complete(Request $request)
    {
        if (session('role') !== 'pelanggan') return redirect('/')->with('error', 'Akses ditolak!');
        
        $order = Order::where('id', $request->input('id'))
                      ->where('user_id', Auth::id())
                      ->firstOrFail();
        
        if ($order->status === 'Dikirim' || $order->status === 'Sedang Dikirim' || $order->status === 'Pesanan Sedang Dikirim') {
            $order->status = 'Selesai';
            
            // Award points if not already awarded
            if (!$order->points_awarded) {
                $user = $order->user;
                if ($user) {
                    $subtotal = $order->items->sum(fn($item) => $item->price * $item->quantity);
                    $pointsEarned = floor($subtotal / 10000);
                    $user->increment('points', $pointsEarned);
                    $order->points_awarded = true;
                }
            }
            
            $order->save();

            // Notify Admins that customer received order
            Notification::send('admin', 'Pesanan Selesai #' . $order->order_number, 'Pelanggan ' . Auth::user()->name . ' telah mengonfirmasi penerimaan pesanan.', null, 'success', '/admin/manajemen-pesanan');

            return redirect()->back()->with('success', 'Pesanan #' . $order->order_number . ' telah dikonfirmasi selesai. Terima kasih!');
        }
        
        return redirect()->back()->with('error', 'Hanya pesanan yang sedang dikirim yang dapat dikonfirmasi.');
    }

    public function downloadInvoice($id)
    {
        if (session('role') !== 'pelanggan') return redirect('/')->with('error', 'Akses ditolak!');
        
        $order = Order::where('id', $id)
                      ->where('user_id', Auth::id())
                      ->where('status', 'Selesai')
                      ->with(['items.book'])
                      ->firstOrFail();

        $pdf = Pdf::loadView('pelanggan.invoice_pdf', ['order' => $order]);
        return $pdf->download('invoice-' . $order->order_number . '.pdf');
    }

    public function status()
    {
        if (session('role') !== 'pelanggan') {
            return redirect('/login')->with('error', 'Silakan login terlebih dahulu.');
        }
        return redirect('/pelanggan/riwayat');
    }
}
