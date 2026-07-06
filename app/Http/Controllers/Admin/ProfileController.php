<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function edit()
    {
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
    }

    public function update(Request $request)
    {
        if (session('role') !== 'admin') return redirect('/')->with('error', 'Akses ditolak!');
        
        $request->validate([
            'name' => 'required|string|min:3|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|digits_between:10,13',
            'address' => 'required|string|min:5',
            'daerah' => 'required|string',
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'name.min' => 'Nama lengkap minimal harus 3 karakter.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'phone.required' => 'Nomor telepon wajib diisi.',
            'phone.digits_between' => 'pastikan nomor anda minimal 10-13 digit',
            'address.required' => 'Alamat lengkap wajib diisi.',
            'address.min' => 'Alamat minimal harus 5 karakter.',
            'daerah.required' => 'Access region wajib diisi.',
        ]);
        
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
            Storage::disk('public')->put('profiles/' . $imageName, base64_decode($image));
            $data['profile_photo'] = '/storage/profiles/' . $imageName;
        } else if ($request->hasFile('profile_photo')) {
            $path = $request->file('profile_photo')->store('profiles', 'public');
            $data['profile_photo'] = '/storage/' . $path;
        }
        
        $admin->update($data);
        
        // Update session name if it was changed
        session(['username' => $admin->name]);
        
        return redirect()->back()->with('success', 'Profil admin berhasil diperbarui!');
    }
}
