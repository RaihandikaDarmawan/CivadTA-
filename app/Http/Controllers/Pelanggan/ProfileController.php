<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function edit()
    {
        if (session('role') !== 'pelanggan') return redirect('/')->with('error', 'Akses ditolak!');
        return view('pelanggan.profil', ['user' => Auth::user()]);
    }

    public function update(Request $request)
    {
        if (session('role') !== 'pelanggan') return redirect('/')->with('error', 'Akses ditolak!');
        
        $request->validate([
            'name' => 'required|string|min:3|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|digits_between:10,13',
            'address' => 'required|string|min:5',
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'name.min' => 'Nama lengkap minimal harus 3 karakter.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'phone.required' => 'Nomor telepon wajib diisi.',
            'phone.digits_between' => 'pastikan nomor anda minimal 10-13 digit',
            'address.required' => 'Alamat lengkap wajib diisi.',
            'address.min' => 'Alamat minimal harus 5 karakter.',
        ]);
        
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
            Storage::disk('public')->put('profiles/' . $imageName, base64_decode($image));
            $data['profile_photo'] = '/storage/profiles/' . $imageName;
        } else if ($request->hasFile('profile_photo')) {
            $path = $request->file('profile_photo')->store('profiles', 'public');
            $data['profile_photo'] = '/storage/' . $path;
        }
        
        $user->update($data);
        return redirect()->back()->with('success', 'Profil berhasil diperbarui!');
    }
}
