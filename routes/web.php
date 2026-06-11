<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Book;
use App\Models\Admin;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Notification;
use App\Models\Banner;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Transaction;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Mail;
use App\Mail\RegistrationSuccess;
use Illuminate\Auth\Events\PasswordReset;

// Disable SSL verification on local environment for Midtrans requests
if (env('APP_ENV', 'local') === 'local') {
    Config::$curlOptions = [
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
        ],
    ];
}

// Landing Page
Route::get('/', function (Request $request) {
    $banners = Banner::where('is_active', true)->orderBy('order_priority', 'asc')->get();
    $search = $request->input('search');
    
    $query = Book::query();
    if ($search) {
        $query->where(function($q) use ($search) {
            $q->where('title', 'like', '%' . $search . '%')
              ->orWhere('author', 'like', '%' . $search . '%')
              ->orWhere('category', 'like', '%' . $search . '%');
        });
    }
    
    return view('welcome', [
        'dummyBooks' => $query->get(),
        'banners' => $banners,
        'search' => $search
    ]);
});

// AUTH ROUTES
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::post('/register', function (Request $request) {
    $request->validate([
        'name' => 'required|string|max:255',
        'username' => 'required|string|max:255|unique:users',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => [
            'required',
            'string',
            'confirmed',
            \Illuminate\Validation\Rules\Password::min(8)
                ->letters()
                ->mixedCase()
                ->numbers(),
        ],
    ], [
        'password.confirmed' => 'Konfirmasi kolom kata sandi tidak sesuai.',
        'password.min' => 'Kata sandi minimal harus 8 karakter.',
        'password.letters' => 'Kata sandi harus mengandung setidaknya satu huruf.',
        'password.mixed' => 'Kata sandi harus mengandung setidaknya 1 huruf besar.',
        'password.numbers' => 'Kata sandi harus mengandung setidaknya satu angka.',
    ]);

    $user = User::create([
        'name' => $request->name,
        'username' => $request->username,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'daerah' => $request->daerah,
        'role' => 'pelanggan',
    ]);

    // Send Registration Success Email
    try {
        \Illuminate\Support\Facades\Mail::to($user->email)->send(new \App\Mail\RegistrationSuccess($user));
    } catch (\Exception $e) {
        // Log error if mail fails, but don't stop the registration process
        \Illuminate\Support\Facades\Log::error('Gagal mengirim email pendaftaran: ' . $e->getMessage());
    }

    return redirect('/login')->with('success', 'Pendaftaran berhasil! Email konfirmasi telah dikirim.');
})->name('register.submit');

Route::post('/login-submit', function (Request $request) {
    $request->validate([
        'username' => 'required|string|max:255',
        'password' => 'required|string',
    ]);

    $username = $request->input('username');
    $password = $request->input('password');
    $role = $request->input('role', 'pelanggan');

    if ($role === 'admin') {
        // Check admin account first
        $admin = Admin::where('username', $username)->first();
        if ($admin && Hash::check($password, $admin->password)) {
            $request->session()->put('username', $admin->name);
            $request->session()->put('admin_id', $admin->id);
            $request->session()->put('role', 'admin');
            return redirect('/admin/dashboard')->with('success', 'Selamat datang, ' . $admin->name . '!');
        }
    } else {
        // Check customer account
        if (Auth::attempt(['username' => $username, 'password' => $password])) {
            $user = Auth::user();
            $request->session()->put('username', $user->name);
            $request->session()->put('role', 'pelanggan');
            return redirect('/pelanggan/dashboard')->with('success', 'Selamat datang, ' . $user->name . '!');
        }
    }

    return redirect()->back()->with('error', 'Username atau password salah!');
})->name('login.submit');

Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/');
})->name('logout');

// PASSWORD RESET ROUTES
Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->name('password.request');

Route::post('/forgot-password', function (Request $request) {
    $request->validate(['email' => 'required|email']);

    $broker = \App\Models\Admin::where('email', $request->email)->exists() ? 'admins' : 'users';

    $status = Password::broker($broker)->sendResetLink(
        $request->only('email')
    );

    return $status === Password::RESET_LINK_SENT
        ? back()->with('success', 'Link reset password telah dikirim ke email Anda.')
        : back()->withErrors(['email' => 'Gagal mengirim link reset password.']);
})->name('password.email');

Route::get('/reset-password/{token}', function (Request $request, $token) {
    return view('auth.reset-password', ['token' => $token, 'email' => $request->email]);
})->name('password.reset');

Route::post('/reset-password', function (Request $request) {
    $request->validate([
        'token' => 'required',
        'email' => 'required|email',
        'password' => [
            'required',
            'confirmed',
            \Illuminate\Validation\Rules\Password::min(8)
                ->letters()
                ->mixedCase()
                ->numbers(),
        ],
    ], [
        'password.confirmed' => 'Konfirmasi kolom kata sandi tidak sesuai.',
        'password.min' => 'Kata sandi minimal harus 8 karakter.',
        'password.letters' => 'Kata sandi harus mengandung setidaknya satu huruf.',
        'password.mixed' => 'Kata sandi harus mengandung setidaknya 1 huruf besar.',
        'password.numbers' => 'Kata sandi harus mengandung setidaknya satu angka.',
    ]);

    $broker = \App\Models\Admin::where('email', $request->email)->exists() ? 'admins' : 'users';

    $status = Password::broker($broker)->reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function ($user, $password) {
            $user->forceFill([
                'password' => Hash::make($password)
            ])->setRememberToken(Str::random(60));

            $user->save();

            event(new PasswordReset($user));
        }
    );

    return $status === Password::PASSWORD_RESET
        ? redirect()->route('login')->with('success', 'Password berhasil diubah. Silakan login kembali.')
        : back()->withErrors(['email' => 'Gagal mereset password. Silakan coba lagi.']);
})->name('password.update');

// ADMIN ROUTES
Route::get('/admin/dashboard', function () {
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
});

Route::get('/admin/manajemen-user', function (Request $request) {
    if (session('role') !== 'admin') return redirect('/')->with('error', 'Akses ditolak!');
    
    $search = $request->input('search');

    $adminsQuery = Admin::query();
    $customersQuery = User::query();

    if ($search) {
        $adminsQuery->where(function($q) use ($search) {
            $q->where('name', 'like', "%$search%")
              ->orWhere('username', 'like', "%$search%");
        });
        $customersQuery->where(function($q) use ($search) {
            $q->where('name', 'like', "%$search%")
              ->orWhere('username', 'like', "%$search%");
        });
    }

    $admins = $adminsQuery->get();
    $customers = $customersQuery->get();

    $totalAdmins = Admin::count();
    $totalCustomers = User::count();
    $totalOrders = Order::count();
    
    return view('admin.manajemen_user', [
        'admins' => $admins,
        'customers' => $customers,
        'totalAdmins' => $totalAdmins,
        'totalCustomers' => $totalCustomers,
        'totalOrders' => $totalOrders
    ]);
});

Route::post('/admin/user/update-points', function (Request $request) {
    if (session('role') !== 'admin') return redirect('/')->with('error', 'Akses ditolak!');
    
    $user = User::findOrFail($request->input('id'));
    $pointsToAdd = (int) $request->input('points');
    
    $user->increment('points', $pointsToAdd);
    
    // Notify Customer about point addition
    Notification::send('pelanggan', 'Poin Loyalty Bertambah!', 'Admin telah menambahkan ' . $pointsToAdd . ' poin ke akun Anda.', $user->id, 'success', '/pelanggan/riwayat');
    
    return redirect()->back()->with('success', 'Poin loyalty untuk ' . $user->name . ' berhasil ditambahkan!');
})->name('admin.user.update-points');

Route::post('/admin/tambah-admin/submit', function (Request $request) {
    if (session('role') !== 'admin') return redirect('/')->with('error', 'Akses ditolak!');
    
    $request->validate([
        'name' => 'required|string|max:255',
        'username' => 'required|string|max:255|unique:admins',
        'email' => 'required|string|email|max:255|unique:admins',
        'password' => [
            'required',
            'string',
            \Illuminate\Validation\Rules\Password::min(8)
                ->letters()
                ->mixedCase()
                ->numbers(),
        ],
    ], [
        'password.confirmed' => 'Konfirmasi kolom kata sandi tidak sesuai.',
        'password.min' => 'Kata sandi minimal harus 8 karakter.',
        'password.letters' => 'Kata sandi harus mengandung setidaknya satu huruf.',
        'password.mixed' => 'Kata sandi harus mengandung setidaknya 1 huruf besar.',
        'password.numbers' => 'Kata sandi harus mengandung setidaknya satu angka.',
    ]);

    Admin::create([
        'name' => $request->name,
        'username' => $request->username,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'daerah' => $request->daerah ?? 'Pusat',
    ]);

    return redirect('/admin/manajemen-user')->with('success', 'Admin baru berhasil ditambahkan ke sistem!');
})->name('admin.tambah.submit');

Route::post('/admin/user/delete', function (Request $request) {
    if (session('role') !== 'admin') return redirect('/')->with('error', 'Akses ditolak!');
    
    $id = $request->input('id');
    $role = $request->input('role');

    if ($role === 'admin') {
        // Prevent deleting self
        if (session('admin_id') == $id) {
            return redirect()->back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri!');
        }
        Admin::findOrFail($id)->delete();
    } else {
        User::findOrFail($id)->delete();
    }

    return redirect()->back()->with('success', 'Akun berhasil dihapus dari sistem!');
});

Route::get('/admin/manajemen-pesanan', function () {
    if (session('role') !== 'admin') return redirect('/')->with('error', 'Akses ditolak!');
    
    $orders = Order::with(['user', 'items.book'])->orderBy('created_at', 'desc')->get();
    
    return view('admin.pesanan', [
        'orders' => $orders
    ]);
});

Route::get('/admin/laporan-penjualan', function (Request $request) {
    if (session('role') !== 'admin') return redirect('/')->with('error', 'Akses ditolak!');
    
    $filter = $request->input('filter', 'all');
    $startDate = $request->input('start_date');
    $endDate = $request->input('end_date');
    
    $query = Order::with(['user', 'items.book'])->orderBy('created_at', 'desc');

    if ($startDate && $endDate) {
        $query->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
        $filter = 'custom';
    } else {
        if ($filter === 'today') {
            $query->whereDate('created_at', now()->today());
        } elseif ($filter === 'week') {
            $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
        } elseif ($filter === 'month') {
            $query->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year);
        } elseif ($filter === 'year') {
            $query->whereYear('created_at', now()->year);
        }
    }

    $orders = $query->get();
    $totalRevenue = $orders->where('status', '!=', 'Cancelled')->sum('total_amount');
    $totalOrders = $orders->count();
    $totalBooks = $orders->where('status', '!=', 'Cancelled')->flatMap->items->sum('quantity');

    return view('admin.laporan', [
        'orders' => $orders,
        'filter' => $filter,
        'startDate' => $startDate,
        'endDate' => $endDate,
        'totalRevenue' => $totalRevenue,
        'totalOrders' => $totalOrders,
        'totalBooks' => $totalBooks
    ]);
});

Route::get('/admin/laporan-penjualan/export', function (Request $request) {
    if (session('role') !== 'admin') return redirect('/')->with('error', 'Akses ditolak!');
    
    $filter = $request->input('filter', 'all');
    $startDate = $request->input('start_date');
    $endDate = $request->input('end_date');
    
    $query = Order::with(['user', 'items.book'])->orderBy('created_at', 'desc');

    if ($startDate && $endDate) {
        $query->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
        $periodLabel = "Custom ($startDate s/d $endDate)";
    } else {
        if ($filter === 'today') {
            $query->whereDate('created_at', now()->today());
        } elseif ($filter === 'week') {
            $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
        } elseif ($filter === 'month') {
            $query->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year);
        } elseif ($filter === 'year') {
            $query->whereYear('created_at', now()->year);
        }
        $periodLabel = strtoupper($filter);
    }

    $orders = $query->get();
    $totalRevenue = $orders->where('status', '!=', 'Cancelled')->sum('total_amount');
    
    $filename = "Laporan_Penjualan_CIVAD_" . date('Y-m-d') . ".csv";
    
    header("Content-Type: text/csv; charset=UTF-8");
    header("Content-Disposition: attachment; filename=$filename");
    header("Pragma: no-cache");
    header("Expires: 0");

    $output = fopen('php://output', 'w');
    
    // Add UTF-8 BOM for Excel support
    fwrite($output, "\xEF\xBB\xBF");
    
    // Tell Excel to use comma as separator
    fwrite($output, "sep=,\r\n");

    fputcsv($output, ["LAPORAN PENJUALAN CIVAD"]);
    fputcsv($output, ["Periode: " . $periodLabel]);
    fputcsv($output, []); // Empty spacer line

    // Header row
    fputcsv($output, ["No. Pesanan", "Tanggal", "Pelanggan", "Total", "Status"]);

    // Data rows
    foreach($orders as $order) {
        fputcsv($output, [
            "#" . $order->order_number,
            $order->created_at->format('d/m/Y H:i'),
            $order->user->name ?? 'Guest',
            "Rp " . number_format($order->total_amount, 0, ',', '.'),
            $order->status
        ]);
    }

    fputcsv($output, []); // Empty spacer line
    fputcsv($output, ["TOTAL PENDAPATAN", "", "", "Rp " . number_format($totalRevenue, 0, ',', '.')]);

    fclose($output);
    exit;
});

Route::post('/admin/pesanan/update-status', function (Request $request) {
    if (session('role') !== 'admin') return redirect('/')->with('error', 'Akses ditolak!');
    
    $order = Order::findOrFail($request->input('id'));
    $order->status = $request->input('status');
    
    if ($request->filled('rejection_reason')) {
        $order->rejection_reason = $request->input('rejection_reason');
    }
    
    if ($request->input('status') === 'Selesai' && !$order->points_awarded) {
        $user = $order->user;
        if ($user) {
            $pointsEarned = floor($order->total_amount / 10000);
            $user->increment('points', $pointsEarned);
            $order->points_awarded = true;
        }
    }

    $order->save();
    
    // Notify Customer about status update
    Notification::send('pelanggan', 'Status Pesanan Diperbarui', 'Pesanan #' . $order->order_number . ' Anda kini berstatus: ' . $order->status, $order->user_id, 'info', '/pelanggan/riwayat');
    
    return redirect()->back()->with('success', 'Status pesanan #' . $order->order_number . ' berhasil diubah menjadi ' . $order->status);
});

Route::get('/admin/manajemen-buku', function (\Illuminate\Http\Request $request) {
    if (session('role') !== 'admin') return redirect('/')->with('error', 'Akses ditolak!');
    
    $query = App\Models\Book::query();
    
    if ($request->has('search') && $request->search != '') {
        $query->where(function($q) use ($request) {
            $q->where('title', 'like', '%' . $request->search . '%')
              ->orWhere('author', 'like', '%' . $request->search . '%');
        });
    }
    
    if ($request->has('category') && $request->category != '') {
        $query->where('category', $request->category);
    }
    
    return view('admin.manajemen_buku', [
        'dummyBooks' => $query->get()
    ]);
});

Route::post('/admin/buku/store', function (Request $request) {
    if (session('role') !== 'admin') return redirect('/')->with('error', 'Akses ditolak!');
    
    $basePrice = (int) str_replace('.', '', $request->input('price'));
    Book::create([
        'title' => $request->input('title'),
        'author' => $request->input('author') ?? 'Admin',
        'category' => $request->input('category'),
        'class' => 'Kelas ' . $request->input('class_level'),
        'base_price' => $basePrice,
        'price' => 'Rp ' . number_format($basePrice, 0, ',', '.'),
        'stock' => (int) $request->input('stock'),
        'image' => $request->input('image') ?? 'https://images.unsplash.com/photo-1543002588-bfa74002ed7e?q=80&w=800&auto=format&fit=crop',
        'desc' => $request->input('desc')
    ]);
    
    return redirect()->back()->with('success', 'Buku baru berhasil ditambahkan ke database!');
});

Route::post('/admin/buku/update', function (Request $request) {
    if (session('role') !== 'admin') return redirect('/')->with('error', 'Akses ditolak!');
    
    $book = Book::findOrFail($request->input('id'));
    $basePrice = (int) str_replace('.', '', $request->input('price'));
    
    $book->update([
        'title' => $request->input('title'),
        'author' => $request->input('author'),
        'category' => $request->input('category'),
        'class' => 'Kelas ' . $request->input('class_level'),
        'base_price' => $basePrice,
        'price' => 'Rp ' . number_format($basePrice, 0, ',', '.'),
        'stock' => (int) $request->input('stock'),
        'desc' => $request->input('desc'),
        'image' => $request->filled('image') ? $request->input('image') : $book->image
    ]);
    
    return redirect()->back()->with('success', 'Data buku di database berhasil diperbarui!');
});

Route::post('/admin/buku/delete', function (Request $request) {
    if (session('role') !== 'admin') return redirect('/')->with('error', 'Akses ditolak!');
    
    $book = Book::findOrFail($request->input('id'));
    $book->delete();
    
    return redirect()->back()->with('success', 'Buku telah dihapus dari database!');
});


// CUSTOMER ROUTES
Route::get('/pelanggan/dashboard', function () {
    if (session('role') !== 'pelanggan') return redirect('/')->with('error', 'Akses ditolak!');
    return view('pelanggan.dashboard', ['dummyBooks' => Book::all()]);
});

Route::get('/pelanggan/buku/{id}', function ($id) {
    $book = Book::findOrFail($id);
    return view('pelanggan.detail', ['book' => $book]);
});

Route::post('/pelanggan/keranjang/tambah', function (Request $request) {
    $book = Book::findOrFail($request->input('buku_id'));
    $qtyToAdd = (int) $request->input('qty', 1);

    $cart = session('cart', []);
    if (isset($cart[$book->id])) {
        $cart[$book->id]['qty'] += $qtyToAdd;
    } else {
        $cart[$book->id] = [
            'id' => $book->id,
            'title' => $book->title,
            'price' => $book->base_price,
            'category' => $book->category,
            'class' => $book->class,
            'qty' => $qtyToAdd
        ];
    }
    session(['cart' => $cart]);
    
    session(['cart_count' => count($cart)]);
    
    return redirect()->back()->with('success', $book->title . ' berhasil ditambahkan ke keranjang!');
});

Route::match(['get', 'post'], '/pelanggan/beli-sekarang', function (Request $request) {
    if ($request->isMethod('get')) {
        return redirect('/pelanggan/dashboard');
    }
    $book = Book::findOrFail($request->input('buku_id'));
    $qtyToAdd = (int) $request->input('qty', 1);

    $cart = session('cart', []);
    if (isset($cart[$book->id])) {
        $cart[$book->id]['qty'] += $qtyToAdd;
    } else {
        $cart[$book->id] = [
            'id' => $book->id,
            'title' => $book->title,
            'price' => $book->base_price,
            'category' => $book->category,
            'class' => $book->class,
            'qty' => $qtyToAdd
        ];
    }
    session(['cart' => $cart]);
    session(['cart_count' => count($cart)]);
    
    return redirect('/pelanggan/pesanan');
});

Route::post('/pelanggan/keranjang/hapus', function (Request $request) {
    $cart = session('cart', []);
    $id = $request->input('id');
    
    if (isset($cart[$id])) {
        unset($cart[$id]);
        session(['cart' => $cart]);
        
        session(['cart_count' => count($cart)]);
    }
    
    return redirect()->back()->with('success', 'Buku berhasil dihapus dari keranjang!');
});

Route::post('/pelanggan/keranjang/update', function (Request $request) {
    $cart = session('cart', []);
    $id = $request->input('id');
    $qty = (int) $request->input('qty');
    
    if (isset($cart[$id]) && $qty > 0) {
        $cart[$id]['qty'] = $qty;
        session(['cart' => $cart]);
        
        session(['cart_count' => count($cart)]);
    }
    
    return response()->json(['success' => true]);
});

Route::post('/pelanggan/konfirmasi-pembayaran', function (Request $request) {
    $cart = session('cart', []);
    if (empty($cart)) return redirect('/pelanggan/dashboard');

    $user = Auth::user();
    if (!$user) return redirect('/login')->with('error', 'Silakan login terlebih dahulu');

    $orderNumber = 'ORD-' . strtoupper(Str::random(8));
    $subtotal = collect($cart)->sum(fn($i) => $i['price'] * $i['qty']);
    $distanceKm = (int) $request->input('distance_km', 3);
    $shipping = $distanceKm * 2800;
    
    $discount = session('active_discount', 0);
    $usePoints = $request->input('use_points') == '1';
    if ($usePoints && $user->points >= 100) {
        $discount += 10000;
    }
    $total = max(0, $subtotal + $shipping - $discount);

    // Konfigurasi Midtrans
    Config::$serverKey = env('MIDTRANS_SERVER_KEY');
    Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
    Config::$isSanitized = true;
    Config::$is3ds = true;

    $order = null;
    DB::transaction(function () use ($user, $orderNumber, $total, $cart, &$order, $request, $shipping, $usePoints) {
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
        ]);

        if ($usePoints && $user->points >= 100) {
            $user->decrement('points', 100);
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

        session(['cart' => []]);
        
        // Notify Admins about new order
        Notification::send('admin', 'Pesanan Baru #' . $orderNumber, 'Pelanggan ' . $user->name . ' baru saja melakukan pemesanan sebesar Rp ' . number_format($total, 0, ',', '.'), null, 'info', '/admin/manajemen-pesanan');
        session(['cart_count' => 0]);
        session(['active_discount' => 0]);

        return view('pelanggan.pembayaran_midtrans', [
            'order' => $order,
            'snapToken' => $snapToken
        ]);
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Gagal terhubung ke Midtrans: ' . $e->getMessage());
    }
});

// Midtrans Notification Webhook
Route::post('/midtrans/callback', function (Request $request) {
    Config::$serverKey = env('MIDTRANS_SERVER_KEY');
    Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);

    $notification = new \Midtrans\Notification();

    $transaction = $notification->transaction_status;
    $type = $notification->payment_type;
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
})->name('midtrans.callback');

// Customer Profile
Route::get('/pelanggan/profil', function () {
    if (session('role') !== 'pelanggan') return redirect('/')->with('error', 'Akses ditolak!');
    return view('pelanggan.profil', ['user' => Auth::user()]);
});

Route::post('/pelanggan/profil/update', function (Request $request) {
    if (session('role') !== 'pelanggan') return redirect('/')->with('error', 'Akses ditolak!');
    
    $user = Auth::user();
    $data = [
        'name' => $request->name,
        'email' => $request->email,
        'phone' => $request->phone,
        'address' => $request->address,
    ];

    if ($request->filled('cropped_photo')) {
        $base64 = $request->cropped_photo;
        $image = str_replace('data:image/png;base64,', '', $base64);
        $image = str_replace(' ', '+', $image);
        $imageName = 'profile_' . $user->id . '_' . time() . '.png';
        \Illuminate\Support\Facades\Storage::disk('public')->put('profiles/' . $imageName, base64_decode($image));
        $data['profile_photo'] = '/storage/profiles/' . $imageName;
    } else if ($request->hasFile('profile_photo')) {
        $path = $request->file('profile_photo')->store('profiles', 'public');
        $data['profile_photo'] = '/storage/' . $path;
    }
    
    $user->update($data);
    return redirect()->back()->with('success', 'Profil berhasil diperbarui!');
});

// Admin Profile
Route::get('/admin/profil', function () {
    if (session('role') !== 'admin') return redirect('/')->with('error', 'Akses ditolak!');
    
    $admin = null;
    if (session()->has('admin_id')) {
        $admin = Admin::find(session('admin_id'));
    } else {
        // Fallback if session admin_id is not set (e.g. older session)
        $admin = Admin::where('name', session('username'))->first();
    }
    
    if (!$admin) return redirect('/')->with('error', 'Admin tidak ditemukan. Silakan login kembali.');
    
    return view('admin.profil', ['admin' => $admin]);
});

Route::post('/admin/profil/update', function (Request $request) {
    if (session('role') !== 'admin') return redirect('/')->with('error', 'Akses ditolak!');
    
    $admin = null;
    if (session()->has('admin_id')) {
        $admin = Admin::find(session('admin_id'));
    } else {
        $admin = Admin::where('name', session('username'))->first();
    }
    
    if (!$admin) return redirect('/')->with('error', 'Admin tidak ditemukan.');

    $data = [
        'name' => $request->name,
        'email' => $request->email,
        'phone' => $request->phone,
        'address' => $request->address,
        'daerah' => $request->daerah,
    ];

    if ($request->filled('cropped_photo')) {
        $base64 = $request->cropped_photo;
        $image = str_replace('data:image/png;base64,', '', $base64);
        $image = str_replace(' ', '+', $image);
        $imageName = 'admin_profile_' . $admin->id . '_' . time() . '.png';
        \Illuminate\Support\Facades\Storage::disk('public')->put('profiles/' . $imageName, base64_decode($image));
        $data['profile_photo'] = '/storage/profiles/' . $imageName;
    } else if ($request->hasFile('profile_photo')) {
        $path = $request->file('profile_photo')->store('profiles', 'public');
        $data['profile_photo'] = '/storage/' . $path;
    }
    
    $admin->update($data);
    
    // Update session name if it was changed
    session(['username' => $admin->name]);
    
    return redirect()->back()->with('success', 'Profil admin berhasil diperbarui!');
});

Route::get('/pelanggan/beranda', function () { return view('pelanggan.beranda'); });
Route::get('/pelanggan/keranjang', function () { return view('pelanggan.keranjang'); });
Route::get('/pelanggan/pesanan', function () {
    if (session('role') !== 'pelanggan') return redirect('/')->with('error', 'Akses ditolak!');
    
    $cart = session('cart', []);
    foreach ($cart as $id => $item) {
        $book = App\Models\Book::find($id);
        if (!$book || $item['qty'] > $book->stock) {
            return redirect('/pelanggan/keranjang')->with('error', 'stok tidak mencukupi, tolong ubah jumlah stok');
        }
    }
    return view('pelanggan.pesanan');
});
Route::match(['get', 'post'], '/pelanggan/pembayaran', function (Request $request) { 
    return view('pelanggan.pembayaran', ['request' => $request]); 
});
Route::get('/pelanggan/riwayat', function () {
    if (session('role') !== 'pelanggan') return redirect('/')->with('error', 'Akses ditolak!');
    
    $orders = Order::where('user_id', Auth::id())
                   ->with('items.book')
                   ->orderBy('created_at', 'desc')
                   ->get();

    // Local Test Fix: Sync status with Midtrans if still pending
    Config::$serverKey = env('MIDTRANS_SERVER_KEY');
    Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);

    foreach ($orders as $order) {
        if ($order->status === 'Pending' && $order->payment_method === 'Midtrans') {
            try {
                $status = Transaction::status($order->order_number);
                if ($status->transaction_status == 'settlement' || ($status->transaction_status == 'capture' && $status->fraud_status !== 'challenge')) {
                    $order->status = 'Pesanan Sedang Dikemas';
                    
                    // Award points if not already awarded
                    $user = $order->user;
                    if ($user && !$order->points_awarded) {
                        $pointsEarned = floor($order->total_amount / 10000);
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
});
Route::post('/pelanggan/pesanan/selesai', function (Request $request) {
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
                $pointsEarned = floor($order->total_amount / 10000);
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
})->name('pelanggan.pesanan.selesai');

Route::get('/pelanggan/status', function () {
    if (session('role') !== 'pelanggan') return redirect('/')->with('error', 'Akses ditolak!');
    
    $orders = Order::where('user_id', Auth::id())
                   ->orderBy('created_at', 'desc')
                   ->get();
                   
    return view('pelanggan.status', ['orders' => $orders]);
});


// Notification Routes
Route::get('/pelanggan/notifications/read-all', function() {
    Notification::where('user_id', Auth::id())->where('role', 'pelanggan')->update(['is_read' => true]);
    return back();
});

Route::get('/admin/notifications/read-all', function() {
    Notification::where('role', 'admin')->update(['is_read' => true]);
    return back();
});
