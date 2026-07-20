<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\RegistrationSuccess;

class AuthController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }

    public function register()
    {
        return view('auth.register');
    }

    public function registerSubmit(Request $request)
    {
        $request->validate([
            'name' => 'required|string|min:3|max:255',
            'username' => 'required|string|min:3|max:255|unique:pelanggan',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:pelanggan',
                'regex:/^[^@]+@[^@]+\.[^@]+$/'
            ],
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
            'email.email' => 'The email must be a valid email address.',
            'email.regex' => 'The email must be a valid email address.',
            'password.confirmed' => 'Konfirmasi kolom kata sandi tidak sesuai.',
            'password.min' => 'Kata sandi minimal harus 8 karakter.',
            'password.letters' => 'Kata sandi harus mengandung setidaknya satu huruf.',
            'password.mixed' => 'Kata sandi harus mengandung setidaknya 1 huruf besar.',
            'password.numbers' => 'Kata sandi harus mengandung setidaknya satu angka.',
            'username.min' => 'Username minimal harus 3 karakter.',
            'name.min' => 'Nama lengkap minimal harus 3 karakter.',
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
            Mail::to($user->email)->send(new RegistrationSuccess($user));
        } catch (\Exception $e) {
            // Log error if mail fails, but don't stop the registration process
            Log::error('Gagal mengirim email pendaftaran: ' . $e->getMessage());
        }

        return redirect('/login')->with('success', 'Pendaftaran berhasil! Email konfirmasi telah dikirim.');
    }

    public function loginSubmit(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
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

        return redirect()->back()->with('error', 'Username/kata sandi salah, silahkan coba lagi');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
