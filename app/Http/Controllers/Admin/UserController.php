<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Admin;
use App\Models\Order;
use App\Models\Notification;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
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
    }

    public function updatePoints(Request $request)
    {
        if (session('role') !== 'admin') return redirect('/')->with('error', 'Akses ditolak!');
        
        $user = User::findOrFail($request->input('id'));
        $pointsToAdd = (int) $request->input('points');
        
        $user->increment('points', $pointsToAdd);
        
        // Notify Customer about point addition
        Notification::send('pelanggan', 'Poin Loyalty Bertambah!', 'Admin telah menambahkan ' . $pointsToAdd . ' poin ke akun Anda.', $user->id, 'success', '/pelanggan/riwayat');
        
        return redirect()->back()->with('success', 'Poin loyalty untuk ' . $user->name . ' berhasil ditambahkan!');
    }

    public function storeAdmin(Request $request)
    {
        if (session('role') !== 'admin') return redirect('/')->with('error', 'Akses ditolak!');
        
        $request->validate([
            'name' => 'required|string|min:3|max:255',
            'username' => 'required|string|min:3|max:255|unique:admins',
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
            'name.min' => 'Nama lengkap minimal harus 3 karakter.',
            'username.min' => 'Username minimal harus 3 karakter.',
        ]);

        Admin::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'daerah' => $request->daerah ?? 'Pusat',
        ]);

        return redirect('/admin/manajemen-user')->with('success', 'Admin baru berhasil ditambahkan ke sistem!');
    }

    public function delete(Request $request)
    {
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
    }
}
