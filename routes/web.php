<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\MidtransCallbackController;
use App\Http\Controllers\Admin;
use App\Http\Controllers\Pelanggan;
use Midtrans\Config;

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
Route::get('/', [HomeController::class, 'index']);

// AUTH ROUTES
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::post('/register', [AuthController::class, 'registerSubmit'])->name('register.submit');
Route::post('/login-submit', [AuthController::class, 'loginSubmit'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// PASSWORD RESET ROUTES
Route::get('/forgot-password', [PasswordResetController::class, 'forgotPassword'])->name('password.request');
Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLink'])->name('password.email');
Route::get('/reset-password/{token}', [PasswordResetController::class, 'resetPassword'])->name('password.reset');
Route::post('/reset-password', [PasswordResetController::class, 'updatePassword'])->name('password.update');

// ADMIN ROUTES
Route::get('/admin/dashboard', [Admin\DashboardController::class, 'index']);
Route::get('/admin/manajemen-user', [Admin\UserController::class, 'index']);
Route::post('/admin/user/update-points', [Admin\UserController::class, 'updatePoints'])->name('admin.user.update-points');
Route::post('/admin/tambah-admin/submit', [Admin\UserController::class, 'storeAdmin'])->name('admin.tambah.submit');
Route::post('/admin/user/delete', [Admin\UserController::class, 'delete']);

Route::get('/admin/manajemen-pesanan', [Admin\OrderController::class, 'index']);
Route::get('/admin/laporan-penjualan', [Admin\OrderController::class, 'salesReport']);
Route::get('/admin/laporan-penjualan/export', [Admin\OrderController::class, 'exportReport']);
Route::post('/admin/pesanan/update-status', [Admin\OrderController::class, 'updateStatus']);

Route::get('/admin/manajemen-buku', [Admin\BookController::class, 'index']);
Route::post('/admin/buku/store', [Admin\BookController::class, 'store']);
Route::post('/admin/buku/update', [Admin\BookController::class, 'update']);
Route::post('/admin/buku/delete', [Admin\BookController::class, 'delete']);

// Admin Return Routes
Route::get('/admin/manajemen-pengembalian', [Admin\ReturnRequestController::class, 'index'])->name('admin.manajemen-pengembalian');
Route::post('/admin/pengembalian/update-status', [Admin\ReturnRequestController::class, 'updateStatus'])->name('admin.pengembalian.update-status');

// CUSTOMER ROUTES
Route::get('/pelanggan/dashboard', [Pelanggan\DashboardController::class, 'dashboard']);
Route::get('/pelanggan/buku/{id}', [Pelanggan\BookController::class, 'show']);

Route::post('/pelanggan/keranjang/tambah', [Pelanggan\CartController::class, 'add']);
Route::match(['get', 'post'], '/pelanggan/beli-sekarang', [Pelanggan\CartController::class, 'buyNow']);
Route::post('/pelanggan/keranjang/hapus', [Pelanggan\CartController::class, 'remove']);
Route::post('/pelanggan/keranjang/update', [Pelanggan\CartController::class, 'update']);

Route::post('/pelanggan/konfirmasi-pembayaran', [Pelanggan\OrderController::class, 'confirmPayment']);
Route::get('/pelanggan/profil', [Pelanggan\ProfileController::class, 'edit']);
Route::post('/pelanggan/profil/update', [Pelanggan\ProfileController::class, 'update']);

// Admin Profile
Route::get('/admin/profil', [Admin\ProfileController::class, 'edit']);
Route::post('/admin/profil/update', [Admin\ProfileController::class, 'update']);

Route::get('/pelanggan/beranda', [Pelanggan\DashboardController::class, 'beranda']);
Route::get('/pelanggan/keranjang', [Pelanggan\CartController::class, 'index']);
Route::get('/pelanggan/pesanan', [Pelanggan\OrderController::class, 'checkout']);
Route::match(['get', 'post'], '/pelanggan/pembayaran', [Pelanggan\OrderController::class, 'payment']);
Route::get('/pelanggan/riwayat', [Pelanggan\OrderController::class, 'history']);
Route::post('/pelanggan/pesanan/selesai', [Pelanggan\OrderController::class, 'complete'])->name('pelanggan.pesanan.selesai');
Route::get('/pelanggan/invoice/{id}/unduh', [Pelanggan\OrderController::class, 'downloadInvoice'])->name('pelanggan.invoice.unduh');
Route::get('/pelanggan/status', [Pelanggan\OrderController::class, 'status']);

// Customer Return Routes
Route::get('/pelanggan/pengembalian/buat', [Pelanggan\ReturnRequestController::class, 'create'])->name('pelanggan.pengembalian.buat');
Route::post('/pelanggan/pengembalian/simpan', [Pelanggan\ReturnRequestController::class, 'store'])->name('pelanggan.pengembalian.simpan');

// Midtrans Notification Webhook
Route::post('/midtrans/callback', [MidtransCallbackController::class, 'handle'])->name('midtrans.callback');

// Notification Routes
Route::get('/pelanggan/notifications/read-all', [NotificationController::class, 'readAllCustomer']);
Route::get('/admin/notifications/read-all', [NotificationController::class, 'readAllAdmin']);

// Chat Routes
Route::get('/pelanggan/chat/{order_id}', [ChatController::class, 'customerChat'])->name('pelanggan.chat');
Route::post('/pelanggan/chat/{order_id}/send', [ChatController::class, 'customerSend'])->name('pelanggan.chat.send');
Route::get('/admin/chat/{order_id}', [ChatController::class, 'adminChat'])->name('admin.chat');
Route::post('/admin/chat/{order_id}/send', [ChatController::class, 'adminSend'])->name('admin.chat.send');
Route::get('/chat/{order_id}/messages', [ChatController::class, 'getMessages']);

// Review Routes
Route::post('/pelanggan/ulasan/simpan', [Pelanggan\ReviewController::class, 'store'])->name('pelanggan.ulasan.simpan');
Route::get('/admin/manajemen-ulasan', [Admin\ReviewController::class, 'index'])->name('admin.manajemen-ulasan');
Route::post('/admin/ulasan/delete', [Admin\ReviewController::class, 'delete'])->name('admin.ulasan.delete');
