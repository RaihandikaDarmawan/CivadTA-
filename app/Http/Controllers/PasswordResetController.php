<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;

class PasswordResetController extends Controller
{
    public function forgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $broker = Admin::where('email', $request->email)->exists() ? 'admins' : 'users';

        $status = Password::broker($broker)->sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with('success', 'Link reset password telah dikirim ke email Anda.')
            : back()->withErrors(['email' => 'Gagal mengirim link reset password.']);
    }

    public function resetPassword(Request $request, $token)
    {
        return view('auth.reset-password', ['token' => $token, 'email' => $request->email]);
    }

    public function updatePassword(Request $request)
    {
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

        $broker = Admin::where('email', $request->email)->exists() ? 'admins' : 'users';

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
    }
}
